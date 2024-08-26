<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "include/config.inc.php";
require "include/dbms.inc.php";
require "include/template2.inc.php";
require "include/auth.inc.php";
require "include/get_by_id.inc.php";

$main = new Template("frame");
$body = new Template("candidates_single");

$profile_id = $mysqli->query("SELECT profile.id FROM `profile` JOIN `user` ON user.id = profile.user_id WHERE user.username = '{$_SESSION["user"]["username"]}'");
$profile_id = ($profile_id->fetch_assoc()['id']);

$img = get_img($mysqli, $profile_id);
$body->setContent("image", $img);

$result = $mysqli->query("
    SELECT
        candidate.name AS name,
        candidate.surname AS surname,
        candidate.age AS age,
        candidate.about AS about,
        expertise.title AS job_title,
        profile.phone AS phone_num,
        profile.email AS email,
        address.country AS country,
        address.city AS city
    FROM candidate 
    JOIN profile ON profile.id = candidate.id
    JOIN profile_expertise ON profile_expertise.profile_id = profile.id
    JOIN expertise ON expertise.id = profile_expertise.expertise_id
    JOIN address ON address.profile_id = profile.id
    WHERE profile.id = '$profile_id'
");
$data = $result->fetch_assoc();
$body->setContent("name", $data['name']);
$body->setContent("surname", $data['surname']);
$body->setContent("age", $data['age']);
$body->setContent("about", $data['about']);
$body->setContent("job_title", $data['job_title']);
$body->setContent("phone_num", $data['phone_num']);
$body->setContent("email", $data['email']);
$body->setContent("country", $data['country']);
$body->setContent("city", $data['city']);

$skills = get_skills($mysqli, $profile_id);
$top_skills_html = '';
$detail_skills_html = '';
foreach ($skills as $skill) {
    $top_skills_html .= "<span>{$skill['name']}</span>";
    $detail_skills_html .= "<div class='progress-sec style2'>
								<span>{$skill['name']}</span>
								<div class='progressbar'>
									<i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i>
								    <div class='progress'>";
	for($i=1;$i<=$skill['level'];$i++)
        $detail_skills_html .= "<i></i>";
    $detail_skills_html .= "</div></div><p>{$skill['level']}0%</p></div>";
}
$body->setContent("top_skills", $top_skills_html);
$body->setContent("detail_skills", $detail_skills_html);

$jobs = get_jobs($mysqli, $profile_id);
$jobs_html = '';
foreach ($jobs as $job) {
    $jobs_html .= " <div class='edu-history style2'>
		                <i></i>
		                <div class='edu-hisinfo'>
		                    <h3>{$job['name']} <span>{$job['emp_name']}</span></h3>
		                    <i>{$job['start']}  to  {$job['end']}</i>
	                        <p>{$job['description']}</p>
		                </div>
	                </div>";
}
$body->setContent("jobs", $jobs_html);

$portfolio = get_portfolio($mysqli, $profile_id);
$portfolio_html = '';
foreach ($portfolio as $img) {
    $portfolio_html .= "<div class='mp-col'>
							<div class='mportolio'><img src='{$img}'
								alt='' /><a href='#' title=''><i class='la la-search'></i></a>
							</div>
						</div>";
}
$body->setContent("portfolio", $portfolio_html);

$main->setContent("body", $body->get());
$main->close();

?>