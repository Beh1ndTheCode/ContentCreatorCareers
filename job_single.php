<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "include/config.inc.php";
require "include/dbms.inc.php";
require "include/template2.inc.php";

$main = new Template("frame");
$body = new Template("job_single");


$result = $mysqli->query("
    SELECT
	    job_offer.name AS job_name, 
	    job_offer.type AS job_type,
	    job_offer.description AS job_description,
	    job_offer.salary AS job_salary,
	    job_offer.date AS job_date,
	    requirement.name AS requirement_name,
	    requirement.description AS requirement_description,
		employer.name AS employer_name,
		profile.phone AS employer_number, 
        profile.email AS employer_email, 
        social_account.uri AS employer_website,
        image.path AS employer_image,
		address.city AS city, 
		address.country AS country,
		address.postal_code AS postal_code,
		address.street AS street,
		address.civic AS civic
	FROM job_offer
    JOIN requirement ON requirement.job_offer_id = job_offer.id
	JOIN employer ON job_offer.employer_id = employer.id
	JOIN profile ON employer.id = profile.id
    JOIN social_account ON profile.id = social_account.profile_id
    JOIN image ON image.profile_id = profile.id
	JOIN address ON employer.id = address.profile_id
    WHERE social_account.name = 'Website'
	");

$data = $result->fetch_assoc();
$body->setContent("job_name", $data['job_name']);
$body->setContent("job_type", $data['job_type']);
$body->setContent("job_description", $data['job_description']);
$body->setContent("requirement_name", $data['requirement_name']);
$body->setContent("requirement_description", $data['requirement_description']);
$body->setContent("job_salary", $data['job_salary']);
$body->setContent("job_date", $data['job_date']);
$body->setContent("employer_name", $data['employer_name']);
$body->setContent("employer_number", $data['employer_number']);
$body->setContent("employer_email", $data['employer_email']);
$body->setContent("employer_website", $data['employer_website']);
$body->setContent("employer_image", $data['employer_image']);
$body->setContent("city", $data['city']);
$body->setContent("country", $data['country']);
$body->setContent("postal_code", $data['postal_code']);
$body->setContent("street", $data['street']);
$body->setContent("civic", $data['civic']);


$main->setContent("body", $body->get());

$main->close();
