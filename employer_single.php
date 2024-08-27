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
$body = new Template("employer_single");

if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
} else {
    die ("Invalid request, missing required parameters.");
}

$job_offer_count = $mysqli->query("SELECT COUNT(*) AS count FROM `job_offer` WHERE employer_id = $id");
$body->setContent("job_offer_count", $job_offer_count->fetch_assoc()['count']);

$result = $mysqli->query("
    SELECT
        employer.name AS name,
        profile.phone AS phone_num,
        profile.email AS email,
        profile.description AS description,
        image.path AS emp_image,
        address.country AS country,
        address.city AS city,
        address.postal_code AS postal_code,
        address.street AS street,
        address.civic AS civic,
        social_account.uri AS website
    FROM 
        employer 
    JOIN 
        profile ON profile.id = employer.id
    LEFT JOIN 
        address ON address.profile_id = profile.id
    LEFT JOIN
        image ON image.profile_id = employer.id AND image.type = 'profilo'    
    LEFT JOIN 
        social_account ON profile.id = social_account.profile_id AND social_account.name = 'website'
    WHERE
        employer.id = $id
");

if (!$result) {
    die("Query failed: " . $mysqli->error);
}

if ($result->num_rows === 0) {
    die("Employer not found.");
}

$data = $result->fetch_assoc();
$image = $data['emp_image'] ?? 'skins/jobhunt/images/profile.png';
$website = $data['website'] ?? 'N/A';
$phone_num = $data['phone_num'] ?? 'N/A';
$email = $data['email'] ?? 'N/A';
$description = $data['description'] ?? 'No Company Information provided';
$city = $data['city'] ?? 'Unknown city';
$country = $data['country'] ?? 'Unknown country';
$postcode = $data['postal_code'] ?? false;
$street = $data['street'] ?? false;
$civic = $data['civic'] ?? false;
if (!$postcode || !$street || !$civic) {
    $body->setContent("address", 'Address not found');
} else {
    $address = $street . ' ' . $civic . ', ' . $postcode;
    $body->setContent("address", $address);
}

$body->setContent("name", $data['name']);
$body->setContent("phone_num", $phone_num);
$body->setContent("email", $email);
$body->setContent("country", $country);
$body->setContent("city", $city);
$body->setContent("image", $image);
$body->setContent("description", $description);
$body->setContent("website", $website);

$result = $mysqli->query("
    SELECT
        job_offer.id AS job_id,
        job_offer.name AS name,
        job_offer.type AS type,
        employer.name AS emp_name,
        address.city AS city,
        address.country AS country,
        DATEDIFF(CURRENT_DATE, job_offer.date) AS date_diff
    FROM 
        employer
    JOIN
        job_offer ON employer.id = job_offer.employer_id
    JOIN 
        address ON employer.id = address.profile_id
    WHERE
        job_offer.employer_id = $id
");

if (!$result) {
    die("Query failed: " . $mysqli->error);
}

$jobs_html = '';
while ($job_offer = $result->fetch_assoc()) {
    $url = "job_single.php?id=" . urlencode($job_offer['job_id']);
    $jobs_html .= " <div class='job-listing wtabs noimg'>
						<div class='job-title-sec'>
							<h3><a href='$url'>{$job_offer['name']}</a></h3>
							<span>{$job_offer['emp_name']}</span>
                            <div class='job-lctn'><i class='la la-map-marker'></i>{$job_offer['city']}, {$job_offer['country']}</div>
                        </div>
                        <div class='job-style-bx'>
							<span class='job-is ft'>{$job_offer['type']}</span>
							<i>{$job_offer['date_diff']} days ago</i>
						</div>
	                </div>";
}
$body->setContent("job_offers", $jobs_html);

$main->setContent("body", $body->get());

$main->close();
