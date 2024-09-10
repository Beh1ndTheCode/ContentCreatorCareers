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
$body = new Template("candidates_list");

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
    $searchQuery .= "candidate.name LIKE ? OR candidate.surname LIKE ? OR job.name LIKE ? OR employer.name LIKE ?";
    $searchParams[] = '%' . $searchTerm . '%';
    $searchParams[] = '%' . $searchTerm . '%';
    $searchParams[] = '%' . $searchTerm . '%';
    $searchParams[] = '%' . $searchTerm . '%';
    $searchTypes .= 'ssss';
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

// Fetch the user role and profile information
$result = $mysqli->query("
    SELECT 
        user_role.role_id, 
        profile.id AS profile_id,
        candidate.name AS candidate_name,
        candidate.surname AS candidate_surname,
        employer.name AS employer_name
    FROM `profile`
    JOIN `user` ON user.id = profile.user_id
    JOIN `user_role` ON user_role.username = user.username
    LEFT JOIN `candidate` ON candidate.id = profile.id AND user_role.role_id = 2
    LEFT JOIN `employer` ON employer.id = profile.id AND user_role.role_id = 3
    WHERE user.username = '{$_SESSION["user"]["username"]}'
");

$data = $result->fetch_assoc();
$profile_id = $data['profile_id'];
$user_role = $data['role_id'];

// Set content based on user role
if ($user_role == 2) {
    $body->setContent("name", $data['candidate_name']);
    $body->setContent("surname", $data['candidate_surname']);
} elseif ($user_role == 3) {
    $body->setContent("name", $data['employer_name']);
}

// Fetch all candidates and their jobs
$query = "
    SELECT
        candidate.id AS can_id,
        candidate.name AS name,
        candidate.surname AS surname,
        address.city AS city,
        address.country AS country,
        job.name AS job_name,
        employer.id AS emp_id,
        employer.name AS emp_name,
        image.path AS img
    FROM `candidate`
    JOIN `profile` ON profile.id = candidate.id 
    LEFT JOIN `image` ON image.profile_id = candidate.id AND image.type = 'profilo'
    LEFT JOIN `address` ON candidate.id = address.profile_id
    LEFT JOIN `job` ON job.candidate_id = candidate.id AND job.type = 'current'
    LEFT JOIN `employer` ON employer.id = job.employer_id
    $searchQuery
";

$stmt = $mysqli->prepare("$query");
if (!empty($searchParams)) {
    $stmt->bind_param($searchTypes, ...$searchParams);
}
$stmt->execute();
$result = $stmt->get_result();

if (!$result) {
    die("Candidate listing query failed: " . $mysqli->error);
}

// Prepare an array to store candidates and their jobs
$candidates = [];

// Process the result set to organize jobs under each candidate
while ($row = $result->fetch_assoc()) {
    $can_id = $row['can_id'];

    // Initialize candidate data if not already set
    if (!isset($candidates[$can_id])) {
        $candidates[$can_id] = ['url' => "candidates_single.php?id=" . urlencode($can_id), 'name' => $row['name'], 'surname' => $row['surname'], 'city' => $row['city'] ?? 'Unknown city', 'country' => $row['country'] ?? 'Unknown country', 'image' => $row['img'] ?? 'skins/jobhunt/images/profile.png', 'jobs' => []];
    }

    // Add job information if job and employer names are available
    if ($row['job_name'] && $row['emp_name']) {
        $job_info = "<i>{$row['job_name']}</i> at <a href='employer_single.php?id=" . urlencode($row['emp_id']) . "'>{$row['emp_name']}</a>";
        $candidates[$can_id]['jobs'][] = $job_info;
    }
}

// Generate the HTML for each candidate
$list_html = '';
foreach ($candidates as $candidate) {
    $jobs = !empty($candidate['jobs']) ? implode(', ', $candidate['jobs']) : '<i>No current job</i>';
    $list_html .= "<div class='emply-resume-list square'>
                        <div class='emply-resume-thumb'>
                            <a href='{$candidate['url']}'><img src='{$candidate['image']}' alt='' /></a>
                        </div>
                        <div class='emply-resume-info'>
                            <h3><a href='{$candidate['url']}' title=''>{$candidate['name']} {$candidate['surname']}</a></h3>
                            <span>$jobs</span>
                            <p><i class='la la-map-marker'></i>{$candidate['city']} / {$candidate['country']}</p>
                        </div>
                    </div><!-- Emply List -->";
}

$body->setContent("list", $list_html);

$main->setContent("body", $body->get());

$main->close();
