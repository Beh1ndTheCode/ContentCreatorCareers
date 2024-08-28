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
$body = new Template("candidates_list");

$profile_id = $mysqli->query("SELECT profile.id FROM `profile` JOIN `user` ON user.id = profile.user_id WHERE user.username = '{$_SESSION["user"]["username"]}'");
$profile_id = ($profile_id->fetch_assoc()['id']);

$result = $mysqli->query("
    SELECT
        candidate.name AS name,
        candidate.surname AS surname
    FROM candidate
    WHERE candidate.id = '$profile_id'
    ");
$data = $result->fetch_assoc();
$body->setContent("name", $data['name']);
$body->setContent("surname", $data['surname']);

$result = $mysqli->query("
	SELECT
        candidate.id AS cand_id,
	    candidate.name AS name,
        candidate.surname AS surname,
	    address.city AS city,
	    address.country AS country,
        job.name AS job_name,
        employer.name AS emp_name,
	    image.path AS img
	FROM 
        candidate
    JOIN 
        profile ON profile.id = candidate.id 
    LEFT JOIN 
        address ON candidate.id = address.profile_id
	LEFT JOIN
        job ON job.candidate_id = candidate.id AND job.type = 'current'
    LEFT JOIN
        employer ON employer.id = job.employer_id
    LEFT JOIN
        image ON image.profile_id = candidate.id AND image.type = 'profilo'
	");

if (!$result) {
    die("Query failed: " . $mysqli->error);
}

if ($result->num_rows === 0) {
    die("No candidate found.");
}

$list_html = '';
while ($candidate = $result->fetch_assoc()) {
    $url = "candidate_single.php?id=" . urlencode($candidate['cand_id']);
    $image = $candidate['img'] ?? 'skins/jobhunt/images/profile.png';
    $city = $candidate['city'] ?? 'Unknown city';
    $country = $candidate['country'] ?? 'Unknown country';
    $job = $candidate['job_name'] ?? 'No current job';
    $emp_name = $candidate['emp_name'] ?? 'Unknown employer';
    $list_html .= "<div class='emply-resume-list square'>
						<div class='emply-resume-thumb'>
							<img src=$image alt='' />
						</div>
						<div class='emply-resume-info'>
							<h3><a href=$url title=''>{$candidate['name']} {$candidate['surname']}</a></h3>
							<span><i>$job</i> at $emp_name</span>
							<p><i class='la la-map-marker'></i>$city / $country</p>
						</div>
						<div class='shortlists'>
							<a href='#' title=''>Shortlist <i class='la la-plus'></i></a>
						</div>
					</div><!-- Emply List -->";
}
$body->setContent("list", $list_html);

$main->setContent("body", $body->get());

$main->close();

?>