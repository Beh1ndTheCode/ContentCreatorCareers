<?php

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "include/config.inc.php";
require "include/dbms.inc.php";
require "include/template2.inc.php";
require "include/auth.inc.php";

$main = new Template("frame");
$body = new Template("employer_resume");

$profile_id = $mysqli->query("SELECT profile.id AS emp_id, employer.name AS emp_name FROM profile JOIN `user` ON user.id = profile.user_id JOIN employer ON profile.id = employer.id WHERE user.username = '{$_SESSION["user"]["username"]}'");
$data = $profile_id->fetch_assoc();
$profile_id = $data['emp_id'];
$emp_name = $data['emp_name'];
$body->setContent("emp_name", $emp_name);

function generateWorkerHtml($candidate) {
    $can_url = "candidates_single.php?id=" . urlencode($candidate['can_id']);
    $job_url = "job_single.php?id=" . urlencode($candidate['job_id']);
    $image = $candidate['can_image'] ?? 'skins/jobhunt/images/profile.png';
    $city = $candidate['can_city'] ?? 'Unknown city';
    $country = $candidate['can_country'] ?? 'Unknown country';

    return " <div class='emply-resume-list'>
                <div class='emply-resume-thumb'>
                    <a href='$can_url' title=''><img alt='' src='$image'/>
                </div>
                <div class='emply-resume-info'>
                    <h3><a href='$can_url' title=''>{$candidate['can_name']} {$candidate['can_surname']}</a></h3>
                    <span><i>{$candidate['job_name']}</i></span>
                    <p><i class='la la-map-marker'></i>$city / $country</p>
                </div>
                <div style='display: table; height: 13vh; width: 100%;'>
                    <div style='display: table-cell; vertical-align: middle;'>
                        <ul class='action_job'>
                            <li><span>View Job</span><a href='$job_url' title=''><i class='la la-eye'></i></a></li>
                            <li><span>Edit</span><a href='#' title=''><i class='la la-pencil'></i></a></li>
                            <li><span>Delete</span><a href='#' title=''><i class='la la-trash-o'></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>";
}

$result = $mysqli->query("
    SELECT
        candidate.id AS can_id,
        candidate.name AS can_name,
        candidate.surname AS can_surname,
        job.id AS job_id,
        job.name AS job_name,
        job.type AS job_type,
        address.city AS can_city,
        address.country AS can_country,
        image.path AS can_image
    FROM 
        employer 
    LEFT JOIN 
        job ON job.employer_id = employer.id
    JOIN
        candidate ON candidate.id = job.candidate_id    
    LEFT JOIN 
        address ON address.profile_id = candidate.id
    LEFT JOIN
        image ON image.profile_id = candidate.id AND image.type = 'profilo'    
    WHERE
        employer.id = $profile_id
");

if (!$result) {
    die("Query failed: " . $mysqli->error);
}

if ($result->num_rows === 0) {
    die("No worker found.");
}

$cur_workers_html = '';
$past_workers_html = '';

while ($candidate = $result->fetch_assoc()) {
    if ($candidate['job_type'] === 'current') {
        $cur_workers_html .= generateWorkerHtml($candidate);
    } elseif ($candidate['job_type'] === 'past') {
        $past_workers_html .= generateWorkerHtml($candidate);
    }
}
$body->setContent("cur_workers", $cur_workers_html);
$body->setContent("past_workers", $past_workers_html);

$main->setContent("body", $body->get());

$main->close();
