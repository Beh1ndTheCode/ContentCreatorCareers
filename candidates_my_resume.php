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
$body = new Template("candidates_my_resume");

$profile_id = $mysqli->query("SELECT profile.id FROM `profile` JOIN `user` ON user.id = profile.user_id WHERE user.username = '{$_SESSION["user"]["username"]}'");
$profile_id = ($profile_id->fetch_assoc()['id']);

$result = $mysqli->query("
    SELECT
        candidate.name AS name,
        candidate.surname AS surname,
        candidate.age AS age,
        profile.description AS about
    FROM candidate
    JOIN profile ON profile.id = candidate.id
    WHERE profile.id = $profile_id
    ");

$data = $result->fetch_assoc();
$body->setContent("name", $data['name']);
$body->setContent("surname", $data['surname']);
$body->setContent("age", $data['age']);
$body->setContent("about", $data['about']);


$skills = get_skills($mysqli,$profile_id);
$skills_html = '';
foreach($skills as $skill){
    $skills_html .= "<div class='progress-sec with-edit' style='padding-left: 15px;'>
                        <span>{$skill['name']}</span>
                        <div class='progressbar' >
                            <div class='progress' style='width: {$skill['level']}0%;'><span>{$skill['level']}0%</span></div>
                        </div>
                        <ul class='action_job'>
                            <li><span>Edit</span><a href='#' title=''><i class='la la-pencil'></i></a></li>
                            <li><span>Delete</span><a href='#' title=''><i class='la la-trash-o'></i></a></li>
                        </ul>
                    </div>";
}
$body->setContent("skills",$skills_html);

$jobs = get_jobs($mysqli,$profile_id);
$jobs_html = '';
foreach($jobs as $job){
    $jobs_html .= "<div class='edu-history style2'>
                        <i></i>
                        <div class='edu-hisinfo'>
                            <h3>{$job['name']} <span>{$job['emp_name']}</span></h3>
                            <i>{$job['start']} - {$job['end']}</i>
                            <p>{$job['description']}</p>
                        </div>
                        <ul class='action_job'>
                            <li><span>Edit</span><a href='#' title=''><i class='la la-pencil'></i></a></li>
                            <li><span>Delete</span><a href='#' title=''><i class='la la-trash-o'></i></a></li>
                        </ul>
                    </div>";
}
$body->setContent("jobs",$jobs_html);

$portfolio = get_portfolio($mysqli,$profile_id);
$portfolio_html = '';
foreach($portfolio as $img){
    $portfolio_html .= "<div class='mp-col'>
						    <div class='mportolio'><img src={$img} alt=''/><a href='#' title=''><i class='la la-search'></i></a>
                            </div>
							<ul class='action_job'>
								<li><span>Edit</span><a href='#' title=''><i class='la la-pencil'></i></a></li>
								<li><span>Delete</span><a href='#' title=''><i class='la la-trash-o'></i></a></li>
							</ul>
						</div>";
}
$body->setContent("portfolio",$portfolio_html);

$main->setContent("body", $body->get());

$main->close();

?>