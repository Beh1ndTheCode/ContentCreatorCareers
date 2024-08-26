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
$body = new Template("employer_single");

$profile_id = $mysqli->query("SELECT profile.id FROM `profile` JOIN `user` ON user.id = profile.user_id WHERE user.username = '{$_SESSION["user"]["username"]}'");
$profile_id = ($profile_id->fetch_assoc()['id']);

$job_offer_count = $mysqli->query("SELECT COUNT(*) AS count FROM `job_offer` WHERE `employer_id` = {$profile_id}");
$body->setContent("job_offer_count", $job_offer_count->fetch_assoc()['count']);

$img = get_img($mysqli, $profile_id);
$body->setContent("image", $img);

$result = $mysqli->query("
    SELECT
        employer.name AS name,
        profile.phone AS phone_num,
        profile.email AS email,
        profile.description AS description,
        address.country AS country,
        address.city AS city,
        address.postal_code AS postal_code,
        address.street AS street,
        address.civic AS civic,
        social_account.uri AS website
    FROM employer 
    JOIN profile ON profile.id = employer.id
    JOIN address ON address.profile_id = profile.id
    JOIN social_account ON profile.id = social_account.profile_id AND social_account.name = 'website'
    WHERE profile.id = '$profile_id'
");

$data = $result->fetch_assoc();
$body->setContent("name", $data['name']);
$body->setContent("phone_num", $data['phone_num']);
$body->setContent("email", $data['email']);
$body->setContent("country", $data['country']);
$body->setContent("city", $data['city']);
$body->setContent("postcode", $data['postal_code']);
$body->setContent("street", $data['street']);
$body->setContent("civic", $data['civic']);
$body->setContent("description", $data['description']);
$body->setContent("website", $data['website']);

$job_offers = get_job_offers_employer($mysqli, $profile_id);
$jobs_html = '';
foreach ($job_offers as $job_offer) {
    $jobs_html .= " <div class='job-listing wtabs noimg'>
						<div class='job-title-sec'>
							<h3><a href='{$job_offer['id']}'>{$job_offer['name']}</a></h3>
							<span>{$job_offer['emp_name']}</span>
                            <div class='job-lctn'><i class='la la-map-marker'></i>{$job_offer['city']}, {$job_offer['country']}</div>
                        </div>
                        <div class='job-style-bx'>
							<span class='job-is ft'>{$job_offer['type']}</span>
							<i>5 months ago</i>
						</div>
	                </div>";
}
$body->setContent("job_offers", $jobs_html);

$main->setContent("body", $body->get());

$main->close();
