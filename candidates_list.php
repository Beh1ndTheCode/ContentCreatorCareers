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
$body = new Template("candidates_list");

$result = $mysqli->query("
    SELECT 
        user_role.role_id, 
        profile.id AS profile_id,
        candidate.name AS candidate_name,
        candidate.surname AS candidate_surname,
        employer.name AS employer_name
    FROM `profile`
    JOIN `user` ON user.id = profile.user_id
    JOIN `user_role` ON user_role.username = user.username
    LEFT JOIN `candidate` ON candidate.id = profile.id AND user_role.role_id = 2
    LEFT JOIN `employer` ON employer.id = profile.id AND user_role.role_id = 3
    WHERE user.username = '{$_SESSION["user"]["username"]}'
");

$data = $result->fetch_assoc();
$profile_id = $data['profile_id'];
$user_role = $data['role_id'];

// Set content based on user role
if ($user_role == 2) {
    $body->setContent("name", $data['candidate_name']);
    $body->setContent("surname", $data['candidate_surname']);
} elseif ($user_role == 3) {
    $body->setContent("name", $data['employer_name']);
}

$result = $mysqli->query("
	SELECT
        candidate.id AS can_id,
	    candidate.name AS name,
        candidate.surname AS surname,
	    address.city AS city,
	    address.country AS country,
        job.name AS job_name,
        employer.id AS emp_id,
        employer.name AS emp_name,
	    image.path AS img
	FROM `candidate`
    JOIN `profile` ON profile.id = candidate.id 
    LEFT JOIN `address` ON candidate.id = address.profile_id
	LEFT JOIN `job` ON job.candidate_id = candidate.id AND job.type = 'current'
    LEFT JOIN `employer` ON employer.id = job.employer_id
    LEFT JOIN `image` ON image.profile_id = candidate.id AND image.type = 'profilo'
	");

if (!$result) {
    die("Query failed: " . $mysqli->error);
}

if ($result->num_rows === 0) {
    die("No candidate found.");
}

$list_html = '';
while ($candidate = $result->fetch_assoc()) {
    $can_url = "candidates_single.php?id=" . urlencode($candidate['can_id']);
    $emp_url = "employer_single.php?id=" . urlencode($candidate['emp_id']);
    $image = $candidate['img'] ?? 'skins/jobhunt/images/profile.png';
    $city = $candidate['city'] ?? 'Unknown city';
    $country = $candidate['country'] ?? 'Unknown country';
    $job = $candidate['job_name'] ?? 'No current job';
    $emp_name = $candidate['emp_name'] ?? null;

    // Check if emp_name is not null or empty
    $employer_display = $emp_name ? " at <a href='$emp_url'>$emp_name</a>" : '';

    $list_html .= "<div class='emply-resume-list square'>
						<div class='emply-resume-thumb'>
							<a href=$can_url><img src=$image alt='' /></a>
						</div>
						<div class='emply-resume-info'>
							<h3><a href=$can_url title=''>{$candidate['name']} {$candidate['surname']}</a></h3>
							<span><i>$job</i>$employer_display</span>
							<p><i class='la la-map-marker'></i>$city / $country</p>
						</div>
					</div><!-- Emply List -->";
}
$body->setContent("list", $list_html);

$main->setContent("body", $body->get());

$main->close();
