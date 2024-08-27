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
        job_offer.type AS job_type,
		employer.name AS emp_name, 
		image.path AS emp_image,
		address.city AS city, 
		address.country AS country,
	    DATEDIFF(CURRENT_DATE, job_offer.date) AS date_diff
	FROM 
	    job_offer
	JOIN 
	    employer ON job_offer.employer_id = employer.id
    LEFT JOIN 
	    image ON image.profile_id = employer.id AND image.type = 'profilo'
	LEFT JOIN 
	    address ON employer.id = address.profile_id
	");

if (!$result) {
    die("Query failed: " . $mysqli->error);
}

if ($result->num_rows === 0) {
    die("No job offers found.");
}

$jobs_html = '';
while ($job = $result->fetch_assoc()) {
    $image = $job['emp_image'] ?? 'skins/jobhunt/images/profile.png';
    $city = $job['city'] ?? 'Unknown city';
    $country = $job['country'] ?? 'Unknown country';
    $type = match ($job['job_type']) {
        "Full time" => 'ft',
        "Part time" => 'pt',
        default => 'fl',
    };
    $jobs_html .= "<div class='job-listing wtabs'>
                        <div class='job-title-sec'>
                            <div class='c-logo'><img alt='' height=auto src='$image' width='70'/></div>
                            <h3><a href='#' title=''>{$job['job_name']}</a></h3>
                            <span>{$job['emp_name']}</span>
                            <div class='job-lctn'><i class='la la-map-marker'></i>$city, $country</div>
                        </div>
                        <div class='job-style-bx'>
                            <span class='job-is $type'>{$job['job_type']}</span>
                            <i>{$job['date_diff']} days ago</i>
                        </div>
                    </div>";
}
$body->setContent("jobs", $jobs_html);

$main->setContent("body", $body->get());

$main->close();
