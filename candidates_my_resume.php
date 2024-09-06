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
    SELECT name, surname
    FROM candidate
    WHERE id = $profile_id
    ");

$data = $result->fetch_assoc();
$body->setContent("name", $data['name']);
$body->setContent("surname", $data['surname']);

$jobs = get_jobs($mysqli, $profile_id);
$jobs_html = '';
foreach ($jobs as $job) {
    $edit_job_url = "candidates_edit_job.php?id=" . urlencode($job['id']);
    $delete_job_url = "remove_job.php?id=" . urlencode($job['id']) . "&type=2";
    $start = DateTime::createFromFormat('Y-m-d', $job['start'])->format('F j, Y');
    if ($job['type'] === 'past') {
        $end = DateTime::createFromFormat('Y-m-d', $job['end'])->format('F j, Y');
    } else {
        $end = 'now';
    }
  
    $jobs_html .= "<div class='edu-history style2'>
                        <i></i>
                        <div class='edu-hisinfo'>
                            <h3>{$job['name']} <span>{$job['emp_name']}</span></h3>
                            <i>$start - $end</i>
                            <p>{$job['description']}</p>
                        </div>
                        <ul class='action_job'>
                            <li><span>Edit</span><a href=$edit_job_url title=''><i class='la la-pencil'></i></a></li>
                            <li><span>Delete</span><a href=$delete_job_url title=''><i class='la la-trash-o'></i></a></li>
                        </ul>
                    </div>";
}
$body->setContent("jobs", $jobs_html);

$portfolio = get_portfolio($mysqli, $profile_id);
$portfolio_html = '';
foreach ($portfolio as $img) {
    $delete_image_url = "remove_image.php?id=" . urlencode($img['id']);
    $portfolio_html .= "<div class='mp-col'>
						    <div class='mportolio'><img src={$img['path']} alt='#'/><a href='#' title=''><i class='la la-search'></i></a>
                            </div>
							<ul class='action_job'>
								<li><span>Edit</span><a href='#' title=''><i class='la la-pencil'></i></a></li>
								<li><span>Delete</span><a href=$delete_image_url title=''><i class='la la-trash-o'></i></a></li>
							</ul>
						</div>";
}
$body->setContent("portfolio", $portfolio_html);

$skills = get_skills($mysqli, $profile_id);
$skills_html = '';
foreach ($skills as $skill) {
    $edit_skill_url = "candidates_edit_skill.php?skill_name=" . urlencode($skill['name']);
    $delete_skill_url = "remove_skill.php?can_id=" . urlencode($profile_id) . "&skill_name=" . urlencode($skill['name']);
    $skills_html .= "<div class='progress-sec with-edit' style='padding-left: 15px;'>
                        <span>{$skill['name']}</span>
                        <div class='progressbar' >
                            <div class='progress' style='width: {$skill['level']}%;'><span>{$skill['level']}%</span></div>
                        </div>
                        <ul class='action_job'>
                            <li><span>Edit</span><a href=$edit_skill_url title=''><i class='la la-pencil'></i></a></li>
                            <li><span>Delete</span><a href=$delete_skill_url title=''><i class='la la-trash-o'></i></a></li>
                        </ul>
                    </div>";
}
$body->setContent("skills", $skills_html);

$main->setContent("body", $body->get());

$main->close();
