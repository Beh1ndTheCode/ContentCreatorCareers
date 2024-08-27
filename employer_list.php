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
$body = new Template("employer_list");

$result = $mysqli->query("SELECT COUNT(*) AS emp_count FROM employer");

$data = $result->fetch_assoc();
$body->setContent("emp_count", $data['emp_count']);

$result = $mysqli->query("
	SELECT
	    employer.name AS emp_name,
	    profile.description AS emp_description,
	    address.city AS city,
	    address.country AS country,
	    expertise.title AS exp_title,
	    image.path AS image,
	    COUNT(job_offer.id) AS job_offer_count
	FROM 
        employer
    JOIN 
        profile ON profile.id = employer.id 
    LEFT JOIN 
        address ON employer.id = address.profile_id
    LEFT JOIN
        profile_expertise ON employer.id = profile_expertise.profile_id
	LEFT JOIN
        expertise ON expertise.id = profile_expertise.expertise_id
	LEFT JOIN
        job_offer ON job_offer.employer_id = employer.id
    LEFT JOIN
        image ON image.profile_id = employer.id AND image.type = 'profilo'
	GROUP BY
	    employer.name, profile.description, address.city, address.country, expertise.title
	");

if (!$result) {
    die("Query failed: " . $mysqli->error);
}

if ($result->num_rows === 0) {
    die("No employers found.");
}

$employers_html = '';
while ($employer = $result->fetch_assoc()) {
    $image = $employer['image'] ?? 'skins/jobhunt/images/profile.png';
    $city = $employer['city'] ?? 'Unknown city';
    $country = $employer['country'] ?? 'Unknown country';
    $exp_title = $employer['exp_title'] ?? 'No expertise listed';
    $employers_html .= "<div class='emply-list'>
							<div class='emply-list-thumb'>
								<a href='#' title=''><img src='{$image}' alt='' /></a>
                            </div>
							<div class='emply-list-info'>
								<div class='emply-pstn'>{$employer['job_offer_count']} Open Position</div>
								<h3><a href='#' title=''>{$employer['emp_name']}</a></h3>
								<span>{$exp_title}</span>
								<h6><i class='la la-map-marker'></i>{$city}, {$country}</h6>
								<p>{$employer['emp_description']}</p>
							</div>
						</div>";
}
$body->setContent("employers", $employers_html);

$main->setContent("body", $body->get());

$main->close();

