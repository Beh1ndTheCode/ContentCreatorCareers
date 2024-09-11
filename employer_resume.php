<?php

session_start();

// Enable detailed error reporting in a development environment
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include necessary files
require "include/config.inc.php";
require "include/dbms.inc.php";
require "include/template2.inc.php";
require "include/auth.inc.php";

$main = new Template("frame");
$body = new Template("employer_resume");

function generateWorkerHtml($candidate)
{
    $can_url = "candidates_single.php?id=" . urlencode($candidate['can_id']);
    $remove_url = "remove_job.php?id=" . urlencode($candidate['job_id']) . "&3";
    $image = $candidate['can_image'] ?? 'skins/jobhunt/images/profile.png';
    $city = $candidate['can_city'] ?? 'Unknown city';
    $country = $candidate['can_country'] ?? 'Unknown country';
    $name = $candidate['can_name'];
    $surname = $candidate['can_surname'];
    $job_name = $candidate['job_name'];
    $formatted_from = DateTime::createFromFormat('Y-m-d', $candidate['job_from'])->format('F j, Y');
    $formatted_to_html = '';
    if ($candidate['job_type'] === 'past') {
        $formatted_to = DateTime::createFromFormat('Y-m-d', $candidate['job_to'])->format('F j, Y');
        $formatted_to_html = "
            <div class='emply-resume-info'>
                <h7>To</h7>
                <p>$formatted_to</p>
            </div>
        ";
    }

    return "<div class='emply-resume-list'>
                <div class='emply-resume-thumb'>
                    <a href='$can_url' title=''><img alt='' src='$image'/></a>
                </div>
                <div class='emply-resume-info'>
                    <h3><a href='$can_url' title=''>$name $surname</a></h3>
                    <span><i>$job_name</i></span>
                    <p><i class='la la-map-marker'></i>$city / $country</p>
                </div>
                <div class='emply-resume-info'>
                    <h7>From</h7>
                    <p>$formatted_from</p>
                </div>
                $formatted_to_html
                <ul class='action_job'>
                    <li><span>Remove Job</span><a href='$remove_url' title=''><i class='la la-trash-o'></i></a></li>
                </ul>
            </div>";
}

$username = $mysqli->real_escape_string($_SESSION['user']['username']);

$result = $mysqli->query("
    SELECT 
        profile.id AS emp_id, 
        employer.name AS emp_name 
    FROM profile 
    JOIN user ON user.id = profile.user_id 
    JOIN employer ON profile.id = employer.id 
    WHERE user.username = '$username'
");

if (!$result) {
    die("Query failed: " . $mysqli->error);
}

$data = $result->fetch_assoc();

$profile_id = $data['emp_id'];
$body->setContent("emp_name", $data['emp_name']);

$result = $mysqli->query("
    SELECT
        candidate.id AS can_id,
        candidate.name AS can_name,
        candidate.surname AS can_surname,
        job_offer.id AS job_id,
        job_offer.name AS job_name,
        application.date AS job_date,
        address.city AS can_city,
        address.country AS can_country,
        image.path AS can_image
    FROM employer
    LEFT JOIN job_offer ON job_offer.employer_id = employer.id
    JOIN application ON application.job_offer_id = job_offer.id
    JOIN candidate ON candidate.id = application.candidate_id
    LEFT JOIN address ON address.profile_id = candidate.id
    LEFT JOIN image ON image.profile_id = candidate.id AND image.type = 'profilo'
    WHERE employer.id = '$profile_id'
");

if (!$result) {
    die("Query failed: " . $mysqli->error);
}

$applied_workers_html = '';
while ($candidate = $result->fetch_assoc()) {
    $can_url = "candidates_single.php?id=" . urlencode($candidate['can_id']);
    $job_url = "job_single.php?id=" . urlencode($candidate['job_id']);
    $remove_url = "remove_application.php?job_id=" . urlencode($candidate['job_id']) . "&can_id=" . urlencode($candidate['can_id']) . "&type=3";
    $image = $candidate['can_image'] ?? 'skins/jobhunt/images/profile.png';
    $city = $candidate['can_city'] ?? 'Unknown city';
    $country = $candidate['can_country'] ?? 'Unknown country';
    $name = $candidate['can_name'];
    $surname = $candidate['can_surname'];
    $job_name = $candidate['job_name'];
    $formatted_date = DateTime::createFromFormat('Y-m-d', $candidate['job_date'])->format('F j, Y');
    $applied_workers_html .= "<div class='emply-resume-list'>
                <div class='emply-resume-thumb'>
                    <a href='$can_url' title=''><img alt='' src='$image'/></a>
                </div>
                <div class='emply-resume-info'>
                    <h3><a href='$can_url' title=''>$name $surname</a></h3>
                    <p><i class='la la-map-marker'></i>$city / $country</p>
                </div>
                <div class='emply-resume-info'>
                    <h7>Job</h7>
                    <span><i><a href='$job_url'>$job_name</a></i></span>
                </div>  
                <div class='emply-resume-info'>
                    <h7>Date</h7>
                    <p>$formatted_date</p>
                </div>
                <ul class='action_job'>
                    <li><span>Reject application</span><a href='$remove_url' title=''><i class='la la-trash-o'></i></a></li>
                </ul>
            </div>";
}

$body->setContent("applied_workers", $applied_workers_html);

$result = $mysqli->query("
    SELECT
        candidate.id AS can_id,
        candidate.name AS can_name,
        candidate.surname AS can_surname,
        job.id AS job_id,
        job.name AS job_name,
        job.type AS job_type,
        job.first_work_date AS job_from,
        job.last_work_date AS job_to,
        address.city AS can_city,
        address.country AS can_country,
        image.path AS can_image
    FROM employer
    LEFT JOIN job ON job.employer_id = employer.id
    JOIN candidate ON candidate.id = job.candidate_id
    LEFT JOIN address ON address.profile_id = candidate.id
    LEFT JOIN image ON image.profile_id = candidate.id AND image.type = 'profilo'
    WHERE employer.id = '$profile_id'
");

if (!$result) {
    die("Query failed: " . $mysqli->error);
}

$cur_workers_html = '';
$past_workers_html = '';

while ($candidate = $result->fetch_assoc()) {
    $worker_html = generateWorkerHtml($candidate);
    if ($candidate['job_type'] === 'current') {
        $cur_workers_html .= $worker_html;
    } elseif ($candidate['job_type'] === 'past') {
        $past_workers_html .= $worker_html;
    }
}

$body->setContent("cur_workers", $cur_workers_html);
$body->setContent("past_workers", $past_workers_html);

$main->setContent("body", $body->get());
$main->close();

