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
$body = new Template("employer_list");

$result = $mysqli->query("SELECT COUNT(*) AS emp_count FROM `employer`");

$data = $result->fetch_assoc();
$body->setContent("emp_count", $data['emp_count']);

$result = $mysqli->query("
	SELECT
	    employer.id AS emp_id,
	    employer.name AS emp_name,
	    profile.description AS emp_description,
	    address.city AS city,
	    address.country AS country,
	    expertise.title AS exp_title,
	    image.path AS image,
	    COUNT(job_offer.id) AS job_offer_count
	FROM `employer`
    JOIN `profile` ON profile.id = employer.id 
    JOIN `image` ON image.profile_id = employer.id AND image.type = 'profilo'
    LEFT JOIN `address` ON employer.id = address.profile_id
    LEFT JOIN `profile_expertise` ON employer.id = profile_expertise.profile_id
	LEFT JOIN `expertise` ON expertise.id = profile_expertise.expertise_id
	LEFT JOIN `job_offer` ON job_offer.employer_id = employer.id
	GROUP BY employer.name, profile.description, address.city, address.country, expertise.title
	");

if (!$result) {
    die("Query failed: " . $mysqli->error);
}

$employers_html = '';
while ($employer = $result->fetch_assoc()) {
    $url = "employer_single.php?id=" . urlencode($employer['emp_id']);
    $city = $employer['city'] ?? 'Unknown city';
    $country = $employer['country'] ?? 'Unknown country';
    $exp_title = $employer['exp_title'] ?? 'No expertise listed';
    $description = $employer['emp_description'] ?? 'No description provided';
    $max_length = 300;
    if (strlen($description) > $max_length) {
        $description = substr($description, 0, $max_length) . '...';
    }
    $employers_html .= "<div class='emply-list'>
							<div class='emply-list-thumb'>
								<a href='$url' title=''><img src='{$employer['image']}' alt='' /></a>
                            </div>
							<div class='emply-list-info'>
								<div class='emply-pstn'>{$employer['job_offer_count']} Open Positions</div>
								<h3><a href='$url' title=''>{$employer['emp_name']}</a></h3>
								<span>$exp_title</span>
								<h6><i class='la la-map-marker'></i>$city, $country</h6>
								<p>$description</p>
							</div>
						</div>";
}
$body->setContent("employers", $employers_html);

$main->setContent("body", $body->get());

$main->close();

