<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "include/config.inc.php";
require "include/dbms.inc.php";
require "include/template2.inc.php";

$main = new Template("frame");
$body = new Template("job_list");

$result = $mysqli->query("SELECT COUNT(*) AS jobs_count FROM `job_offer`");

$data = $result->fetch_assoc();
$body->setContent("jobs_count", $data['jobs_count']);

$result = $mysqli->query("
	SELECT
	    job_offer.id AS job_id,
	    job_offer.name AS job_name,
        job_offer.type AS job_type,
        employer.id AS emp_id,
		employer.name AS emp_name, 
		image.path AS emp_image,
		address.city AS city, 
		address.country AS country,
	    DATEDIFF(CURRENT_DATE, job_offer.date) AS date_diff
	FROM `job_offer`
	JOIN `employer` ON job_offer.employer_id = employer.id
    LEFT JOIN `image` ON image.profile_id = employer.id AND image.type = 'profilo'
	LEFT JOIN `address` ON employer.id = address.profile_id
	");

if (!$result) {
    die("Query failed: " . $mysqli->error);
}

$jobs_html = '';
while ($job = $result->fetch_assoc()) {
    $job_url = "job_single.php?id=" . urlencode($job['job_id']);
    $emp_url = "employer_single.php?id=" . urlencode($job['emp_id']);
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
                            <div class='c-logo'>
                                <a href='$job_url'><img alt='' src='$image' height='96' width='96'/></a>
                            </div>
                            <h3><a href='$job_url'>{$job['job_name']}</a></h3>
                            <span><a href='$emp_url'>{$job['emp_name']}</a></span>
                            <div class='job-lctn'>
                                <i class='la la-map-marker'></i>
                                $city, $country
                            </div>
                        </div>
                        <div class='job-style-bx'>
                            <span class='job-is $type'>
                                {$job['job_type']}
                            </span>
                            <i>{$job['date_diff']} days ago</i>
                        </div>
                    </div>";
}
$body->setContent("jobs", $jobs_html);

$main->setContent("body", $body->get());

$main->close();
