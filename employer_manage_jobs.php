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
$body = new Template("employer_manage_jobs");

$profile_id = $mysqli->query("SELECT profile.id FROM `profile` JOIN `user` ON user.id = profile.user_id WHERE user.username = '{$_SESSION["user"]["username"]}'");
$profile_id = ($profile_id->fetch_assoc()['id']);

$result = $mysqli->query("SELECT employer.name AS emp_name FROM employer WHERE employer.id = $profile_id");
$data = $result->fetch_assoc();
$body->setContent("emp_name", $data['emp_name']);

$result = $mysqli->query("SELECT COUNT(*) AS job_offer_count FROM job_offer WHERE job_offer.employer_id = $profile_id");
$data = $result->fetch_assoc();
$body->setContent("job_offer_count", $data['job_offer_count']);

$result = $mysqli->query("SELECT COUNT(*) AS app_count FROM application JOIN job_offer ON application.job_offer_id = job_offer.id AND job_offer.employer_id = $profile_id");
$data = $result->fetch_assoc();
$body->setContent("app_count", $data['app_count']);

$result = $mysqli->query("SELECT COUNT(*) AS job_count FROM job WHERE job.employer_id = $profile_id");
$data = $result->fetch_assoc();
$body->setContent("job_count", $data['job_count']);

$result = $mysqli->query("
	SELECT
	    job_offer.id AS id,
	    job_offer.name AS name,
	    address.city AS city,
	    address.country AS country,
	    COUNT(application.candidate_id) AS job_app_count,
	    job_offer.date AS date,
	    job_offer.type AS type,
	    job_offer.quantity AS quantity
	FROM 
	    job_offer
	JOIN 
	    employer ON job_offer.employer_id = employer.id
    LEFT JOIN 
        address ON address.profile_id = employer.id
	LEFT JOIN
	    application ON application.job_offer_id = job_offer.id
	WHERE
	    job_offer.employer_id = $profile_id
	GROUP BY 
        job_offer.id, job_offer.name, address.city, address.country, job_offer.date
    ");

if (!$result) {
    die("Query failed: " . $mysqli->error);
}

if ($result->num_rows === 0) {
    die("No job offers found.");
}

$jobs_html = '';
while ($job = $result->fetch_assoc()) {
    $view_url = "job_single.php?id=" . urlencode($job['id']);
    $city = $job['city'] ?? 'Unknown city';
    $country = $job['country'] ?? 'Unknown country';
    $formatted_date = DateTime::createFromFormat('Y-m-d', $job['date'])->format('F j, Y');
    $jobs_html .= "<tr>
                        <td>
						    <div class='table-list-title'>
							    <h3><a href='$view_url' title=''>{$job['name']}</a></h3>
                                <span><i class='la la-map-marker'></i>$city, $country</span>
                            </div>
                        </td>
						<td>
							<span class='applied-field'>{$job['job_app_count']} Applied</span>
						</td>
						<td>
							<span>$formatted_date</span>
						</td>
						<td>
							<span class='status active'>{$job['type']}</span>
						</td>
						<td>
							<span>{$job['quantity']} Positions</span>
						</td>
						<td>
							<ul class='action_job'>
								<li><span>View Job</span><a href='$view_url' title=''><i class='la la-eye'></i></a></li>
                                <li><span>Edit</span><a href='#' title=''><i class='la la-pencil'></i></a></li>
                                <li><span>Delete</span><a href='#' title=''><i class='la la-trash-o'></i></a></li>
                            </ul>
                        </td>
                    </tr>";
}
$body->setContent("jobs", $jobs_html);

$main->setContent("body", $body->get());

$main->close();

