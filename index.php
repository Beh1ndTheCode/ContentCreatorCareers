<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "include/config.inc.php";
require "include/dbms.inc.php";
require "include/template2.inc.php";

$main = new Template("frame");
$body = new Template("index");

$result = $mysqli->query("SELECT COUNT(*) AS jobs_count FROM job_offer");

$data = $result->fetch_assoc();
$body->setContent("jobs_count", $data['jobs_count']);


$result = $mysqli->query("SELECT COUNT(*) AS jobs_today_count FROM job_offer WHERE date = CURRENT_DATE");

$data = $result->fetch_assoc();
$body->setContent("jobs_today_count", $data['jobs_today_count']);


$result = $mysqli->query("
	SELECT title, COUNT(*) AS jobs_count_category FROM expertise
	JOIN profile_expertise ON profile_expertise.expertise_id = expertise.id
	JOIN profile ON profile_expertise.profile_id = profile.id
	JOIN employer ON profile.id = employer.id
	JOIN job_offer ON employer.id = job_offer.employer_id
	");

$data = $result->fetch_assoc();
$body->setContent("expertise", $data['title']);
$body->setContent("jobs_count_category", $data['jobs_count_category']);


$result = $mysqli->query("
	SELECT
	    job_offer.name AS job_name, 
		employer.name AS employer_name, 
		address.city AS city, 
		address.country AS country,
		job_offer.type AS job_type
	FROM job_offer
	JOIN employer ON job_offer.employer_id = employer.id
	JOIN address ON employer.id = address.profile_id
	");

$data = $result->fetch_assoc();
$body->setContent("job_name", $data['job_name']);
$body->setContent("employer_name", $data['employer_name']);
$body->setContent("city", $data['city']);
$body->setContent("country", $data['country']);
$body->setContent("job_type", $data['job_type']);


$main->setContent("body", $body->get());

$main->close();
