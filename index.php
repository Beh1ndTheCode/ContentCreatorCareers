<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "include/config.inc.php";
require "include/dbms.inc.php";
require "include/template2.inc.php";

$main = new Template("frame");
$body = new Template("index");

$jobsResult = $mysqli->query("SELECT COUNT(*) AS jobs_count FROM `job_offer`");
$body->setContent("jobs_count", $jobsResult->fetch_assoc()['jobs_count']);

$jobsTodayResult = $mysqli->query("SELECT COUNT(*) AS jobs_today_count FROM `job_offer` WHERE date = CURRENT_DATE");
$body->setContent("jobs_today_count", $jobsTodayResult->fetch_assoc()['jobs_today_count']);

$expertiseResult = $mysqli->query("
	SELECT expertise.title, COUNT(*) AS jobs_count_category 
	FROM `expertise`
	JOIN `profile_expertise` ON profile_expertise.expertise_id = expertise.id
	JOIN `employer` ON profile_expertise.profile_id = employer.id
	JOIN `job_offer` ON employer.id = job_offer.employer_id
	GROUP BY expertise.title
	LIMIT 8
	");

$expertise_html = '';
while ($expertise = $expertiseResult->fetch_assoc()) {
    $expertise_html .= "<div class='col-lg-3 col-md-3 col-sm-6'>
                            <div class='p-category'>
                                <a href='job_list.php'>
                                    <i class='la la-bullhorn'></i>
                                    <span>{$expertise['title']}</span>
                                    <p>({$expertise['jobs_count_category']} open positions)</p>
                                </a>
                            </div>
                        </div>";
}
$body->setContent("expertise_areas", $expertise_html);

$jobsResult = $mysqli->query("
	SELECT
	    job_offer.id AS job_id,
	    job_offer.name AS job_name, 
        job_offer.type AS job_type,
        employer.id AS employer_id,
		employer.name AS employer_name, 
		image.path AS employer_image,
		address.city AS city, 
		address.country AS country
	FROM `job_offer`
	JOIN `employer` ON job_offer.employer_id = employer.id
    LEFT JOIN `image` ON employer.id = image.profile_id AND image.type = 'profilo'
	LEFT JOIN `address` ON employer.id = address.profile_id
	ORDER BY job_offer.date DESC
	LIMIT 10
	");

$jobs_html = '';
while ($job = $jobsResult->fetch_assoc()) {
    $job_url = "job_single.php?id=" . urlencode($job['job_id']);
    $emp_url = "employer_single.php?id=" . urlencode($job['employer_id']);
    $image = $job['employer_image'] ?? 'skins/jobhunt/images/profile.png';
    $city = $job['city'] ?? 'Unknown city';
    $country = $job['country'] ?? 'Unknown country';
    $type = match ($job['job_type']) {
        "Full time" => 'ft',
        "Part time" => 'pt',
        default => 'fl',
    };
    $jobs_html .= "<div class='job-listing'>
                        <div class='job-title-sec'>
                            <div class='c-logo'>
                                <a href='$job_url'><img alt='' src='$image' height='96' width='96'/></a>
                            </div>
                            <h3><a href='$job_url'>{$job['job_name']}</a></h3>
                            <span><a href='$emp_url'>{$job['employer_name']}</a></span>
                        </div>
                        <span class='job-lctn'>
                            <i class='la la-map-marker'></i>
                            $city, $country
                        </span>
                        <span class='job-is $type'>
                            {$job['job_type']}
                        </span>
                    </div>";
}
$body->setContent("jobs", $jobs_html);

$main->setContent("body", $body->get());

$main->close();
