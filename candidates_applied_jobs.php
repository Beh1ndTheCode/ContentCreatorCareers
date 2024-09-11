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
$body = new Template("candidates_applied_jobs");

$profileResult = $mysqli->query("
    SELECT profile.id, candidate.name, candidate.surname 
    FROM `profile`
    JOIN `candidate` ON profile.id = candidate.id
    JOIN `user` ON user.id = profile.user_id 
    WHERE user.username = '{$_SESSION["user"]["username"]}'
    ");
$profileData = $profileResult->fetch_assoc();
$id = ($profileData['id']);

$body->setContent("name", $profileData['name']);
$body->setContent("surname", $profileData['surname']);

$result = $mysqli->query("
	SELECT
	    application.date AS app_date,
	    job_offer.id AS job_id,
	    job_offer.name AS job_name,
	    employer.id AS emp_id,
	    employer.name AS emp_name,
	    address.city AS emp_city,
	    address.country AS emp_country
	FROM `application`
	JOIN `job_offer` ON job_offer.id = application.job_offer_id
    JOIN `employer` ON employer.id = job_offer.employer_id
	LEFT JOIN `address` ON address.profile_id = employer.id
	WHERE application.candidate_id = $id
    ");

if (!$result) {
    die("Query failed: " . $mysqli->error);
}

$applied_jobs_html = '';
while ($application = $result->fetch_assoc()) {
    $emp_url = "employer_single.php?id=" . urlencode($application['emp_id']);
    $view_url = "job_single.php?id=" . urlencode($application['job_id']);
    $remove_url = "remove_application.php?job_id=" . urlencode($application['job_id']) . "&can_id=" . urlencode($id) . "&type=2";
    $city = $skill['city'] ?? 'Unknown city';
    $country = $skill['country'] ?? 'Unknown country';
    $formatted_date = DateTime::createFromFormat('Y-m-d', $application['app_date'])->format('F j, Y');

    $applied_jobs_html .= "
        <tr>
            <td>
                <div class='table-list-title'>
                    <i><a href='$emp_url'>{$application['emp_name']}</a></i><br />
                    <span><i class='la la-map-marker'></i>{$application['emp_city']}, {$application['emp_country']}</span>
                </div>
            </td>
            <td>
                <div class='table-list-title'>
                    <h3><a href=$view_url title=''>{$application['job_name']}</a></h3>
                </div>
            </td>
            <td>
                <span>$formatted_date</span><br />
            </td>
            <td>
                <ul class='action_job'>
                    <li><span>Delete</span><a href='$remove_url' title=''><i class='la la-trash-o'></i></a></li>
                </ul>
            </td>
        </tr>";
}

$body->setContent("applied_jobs", $applied_jobs_html);

$main->setContent("body", $body->get());

$main->close();
