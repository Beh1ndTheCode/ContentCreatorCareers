<?php

// Error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include necessary files
require "include/config.inc.php";
require "include/dbms.inc.php";
require "include/template2.inc.php";

// Initialize templates
$main = new Template("frame");
$body = new Template("job_list");

// Check if search terms are set
$searchTerm = $_GET['name_search'] ?? '';
$locationSearch = $_GET['location_search'] ?? '';

// Set the search terms in the template for maintaining the values in search inputs
$body->setContent("name_search", htmlspecialchars($searchTerm));
$body->setContent("location_search", htmlspecialchars($locationSearch));

$searchQuery = '';
$searchParams = [];
$searchTypes = '';

// Modify the query if search terms are present
if ($searchTerm) {
    // Add condition for job name
    $searchQuery .= "job_offer.name LIKE ? OR employer.name LIKE ?";
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

// Query to get the count of jobs with optional search conditions
$countQuery = "
    SELECT COUNT(*) AS jobs_count
    FROM `job_offer`
    JOIN `employer` ON job_offer.employer_id = employer.id
    LEFT JOIN `address` ON employer.id = address.profile_id
    $searchQuery
";

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
$jobs_count = $countData['jobs_count'] ?? 0;
$body->setContent("jobs_count", $jobs_count);

// Prepare the main job offers query with possible search conditions
$query = "
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
    $searchQuery
    GROUP BY job_offer.id
";

// Prepare and execute the job listing query
$stmt = $mysqli->prepare($query);
if (!empty($searchParams)) {
    $stmt->bind_param($searchTypes, ...$searchParams);
}
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Job listing query failed: " . $mysqli->error);
}

// Generate job listings HTML
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

// Set job listings in the template
$body->setContent("jobs", $jobs_html);

// Set content and display templates
$main->setContent("body", $body->get());
$main->close();
