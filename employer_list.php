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

$searchTerm = $_GET['search_keyword'] ?? '';
$locationSearch = $_GET['location_search'] ?? '';

$body->setContent("search_keyword", htmlspecialchars($searchTerm));
$body->setContent("location_search", htmlspecialchars($locationSearch));

$searchQuery = '';
$searchParams = [];
$searchTypes = '';

// Modify the query if search terms are present
if ($searchTerm) {
    // Add condition for job name
    $searchQuery .= "employer.name LIKE ? OR expertise.title LIKE ?";
    $searchParams[] = '%' . $searchTerm . '%';
    $searchParams[] = '%' . $searchTerm . '%';
    $searchTypes .= 'ss';
}

if ($locationSearch) {
    // Add condition for city name
    if (!empty($searchQuery)) {
        $searchQuery .= " AND ";
    }
    $searchQuery .= "address.city LIKE ? OR address.country LIKE ?";
    $searchParams[] = '%' . $locationSearch . '%';
    $searchParams[] = '%' . $locationSearch . '%';
    $searchTypes .= 'ss';
}

if (!empty($searchQuery)) {
    $searchQuery = "WHERE " . $searchQuery;
}

$countQuery = "SELECT COUNT(DISTINCT employer.id) AS emp_count FROM `employer`
LEFT JOIN `address` ON employer.id = address.profile_id
LEFT JOIN `profile_expertise` ON employer.id = profile_expertise.profile_id
LEFT JOIN `expertise` ON expertise.id = profile_expertise.expertise_id
$searchQuery";

// Prepare and execute the count query
$countStmt = $mysqli->prepare($countQuery);
if (!empty($searchParams)) {
    $countStmt->bind_param($searchTypes, ...$searchParams);
}
$countStmt->execute();
$countResult = $countStmt->get_result();

if (!$countResult) {
    die("Count query failed: " . $mysqli->error);
}

// Fetch and set the job count
$countData = $countResult->fetch_assoc();
$emp_count = $countData['emp_count'] ?? 0;
$body->setContent("emp_count", $emp_count);

$query = "
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
    LEFT JOIN `image` ON image.profile_id = employer.id AND image.type = 'profilo'
    LEFT JOIN `address` ON employer.id = address.profile_id
    LEFT JOIN `profile_expertise` ON employer.id = profile_expertise.profile_id
	LEFT JOIN `expertise` ON expertise.id = profile_expertise.expertise_id
	LEFT JOIN `job_offer` ON job_offer.employer_id = employer.id
	$searchQuery
	GROUP BY employer.name, profile.description, address.city, address.country, expertise.title
	";

$stmt = $mysqli->prepare("$query");
if (!empty($searchParams)) {
    $stmt->bind_param($searchTypes, ...$searchParams);
}
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Employer listing query failed: " . $mysqli->error);
}

$employers_html = '';
while ($employer = $result->fetch_assoc()) {
    $url = "employer_single.php?id=" . urlencode($employer['emp_id']);
    $image = $employer['image'] ?? "skins/jobhunt/images/profile.png";
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
								<a href='$url' title=''><img src='$image' alt='' /></a>
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

