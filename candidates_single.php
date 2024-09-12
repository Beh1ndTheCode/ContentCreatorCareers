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

if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
} else {
    $profile_id = $mysqli->query("SELECT profile.id FROM `profile` JOIN `user` ON user.id = profile.user_id WHERE user.username = '{$_SESSION["user"]["username"]}'");
    $id = ($profile_id->fetch_assoc()['id']);
}

$img = get_img($mysqli, $id);
$body->setContent("image", $img);

$result = $mysqli->query("
    SELECT
        candidate.id AS id,
        candidate.name AS name,
        candidate.surname AS surname,
        candidate.age AS age,
        expertise.title AS job_title,
        profile.phone AS phone_num,
        profile.email AS email,
        profile.description AS about,
        address.country AS country,
        address.city AS city
    FROM candidate 
    JOIN profile ON profile.id = candidate.id
    LEFT JOIN profile_expertise ON profile_expertise.profile_id = profile.id
    LEFT JOIN expertise ON expertise.id = profile_expertise.expertise_id
    LEFT JOIN address ON address.profile_id = profile.id
    WHERE profile.id = '$id'
");

$data = $result->fetch_assoc();
$about = $data['about'] ?? 'No description found';
$job_title = $data['job_title'] ?? 'No current job';
$job_title = $data['job_title'] ?? 'Unknown Job title';
$email = $data['email'] ?? 'No email found';
$country = $data['country'] ?? 'Unknown country';
$city = $data['city'] ?? 'Unknown city';
$age = $data['age'] ?? 'Unknown age';


$body->setContent("name", $data['name']);
$body->setContent("surname", $data['surname']);
$body->setContent("about", $about);
$body->setContent("job_title", $job_title);
$body->setContent("email", $email);
$body->setContent("country", $country);
$body->setContent("city", $city);
$body->setContent('age',$age);

$socials_sql = $mysqli->query("
    SELECT
        social_account.name AS social_name,
        social_account.uri AS social_uri
    FROM social_account
    JOIN candidate ON candidate.id = social_account.profile_id
    WHERE candidate.id = '{$data['id']}'
");

if (!$socials_sql) {
    die("Query failed: " . $mysqli->error);
}

$socials = [];
while ($social = $socials_sql->fetch_assoc()) {
    $socials[] = ['name' => $social['social_name'], 'uri' => $social['social_uri']];
}
function getUri($socialArray, $name)
{
    foreach ($socialArray as $social) {
        if ($social['name'] === $name) {
            return $social['uri'];
        }
    }
    return null; // Restituisce null se il nome non è trovato
}

$body->setContent('website', getUri($socials, 'website'));
$body->setContent('facebook', getUri($socials, 'facebook'));
$body->setContent('instagram', getUri($socials, 'instagram'));


$skills = get_skills($mysqli, $id);
$top_skills_html = '';
$detail_skills_html = '';
if (empty($skills)) $body->setContent("no_skills", 'No skill found'); else
    $body->setContent("no_skills", '');
foreach ($skills as $skill) {
    $top_skills_html .= "<span>{$skill['name']}</span>";
    $detail_skills_html .= "<div class='progress-sec style2'>
								<span>{$skill['name']}</span>
								<div class='progressbar'>
									<i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i><i></i>
								    <div class='progress'>";
    $num_points = floor($skill['level'] / 10);  // floor ensures integer value
    for ($i = 1; $i <= $num_points; $i++) {
        $detail_skills_html .= "<i></i>";
    }
    $detail_skills_html .= "</div></div><p>{$skill['level']}%</p></div>";
    $detail_skills_html .= "<p>{$skill['description']}</p><span></span>";
}
$body->setContent("top_skills", $top_skills_html);
$body->setContent("detail_skills", $detail_skills_html);

$jobs = get_jobs($mysqli, $id);
$jobs_html = '';
if (empty($jobs)) $body->setContent("no_jobs", 'No job found'); else
    $body->setContent("no_jobs", '');
foreach ($jobs as $job) {
    $start = DateTime::createFromFormat('Y-m-d', $job['start'])->format('F j, Y');
    if ($job['type'] === 'past') {
        $end = DateTime::createFromFormat('Y-m-d', $job['end'])->format('F j, Y');
    } else {
        $end = 'now';
    }
    $jobs_html .= " <div class='edu-history style2'>
		                <i></i>
		                <div class='edu-hisinfo'>
		                    <h3>{$job['name']} <span>{$job['emp_name']}</span></h3>
		                    <i>$start  to  $end</i>
	                        <p>{$job['description']}</p>
		                </div>
	                </div>";
}
$body->setContent("jobs", $jobs_html);

$portfolio = get_portfolio($mysqli, $id);
$portfolio_html = '';
if (empty($portfolio)) $body->setContent("no_portfolio", 'The portfolio is empty'); else
    $body->setContent("no_portfolio", '');
foreach ($portfolio as $img) {
    $portfolio_html .= "<div class='mp-col'>
							<div class='mportolio'><img src={$img['path']} alt=''/>
							    <a href='#' title=''><i class='la la-search'></i></a>
							</div>
						</div>";
}
$body->setContent("portfolio", $portfolio_html);

$main->setContent("body", $body->get());
$main->close();
