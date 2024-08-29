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
                <div class='del-resume'>
                    <a href='#' title=''><i class='la la-trash-o'></i></a>
                </div>
            </div>";
}

$username = $mysqli->real_escape_string($_SESSION['user']['username']);

$result = $mysqli->query("
    SELECT 
        profile.id AS emp_id, 
        employer.name AS emp_name 
    FROM 
        profile 
    JOIN 
        user ON user.id = profile.user_id 
    JOIN 
        employer ON profile.id = employer.id 
    WHERE 
        user.username = '$username'
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
        job.name AS job_name,
        job.type AS job_type,
        job.first_work_date AS job_from,
        job.last_work_date AS job_to,
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
        employer.id = '$profile_id'
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

