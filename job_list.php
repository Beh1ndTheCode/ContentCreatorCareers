<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "include/config.inc.php";
require "include/dbms.inc.php";
require "include/template2.inc.php";

$main = new Template("frame");
$body = new Template("job_list");

$result = $mysqli->query("SELECT COUNT(*) AS jobs_count FROM job_offer");

$data = $result->fetch_assoc();
$body->setContent("jobs_count", $data['jobs_count']);


$result = $mysqli->query("
	SELECT
	    job_offer.name AS job_name, 
		employer.name AS employer_name, 
		image.path AS employer_image,
		address.city AS city, 
		address.country AS country,
		job_offer.type AS job_type,
	    DATEDIFF(CURRENT_DATE, job_offer.date) AS date_diff
	FROM job_offer
	JOIN employer ON job_offer.employer_id = employer.id
    JOIN profile ON employer.id = profile.id
    JOIN image ON image.profile_id = profile.id
	JOIN address ON employer.id = address.profile_id
	");

$data = $result->fetch_assoc();
$body->setContent("job_name", $data['job_name']);
$body->setContent("employer_name", $data['employer_name']);
$body->setContent("employer_image", $data['employer_image']);
$body->setContent("city", $data['city']);
$body->setContent("country", $data['country']);
$body->setContent("job_type", $data['job_type']);
$body->setContent("date_diff", $data['date_diff']);


$main->setContent("body", $body->get());

$main->close();
