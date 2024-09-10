<?php

session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "include/config.inc.php";
require "include/dbms.inc.php";

$profile_id = $mysqli->query("SELECT profile.id FROM `profile` JOIN `user` ON user.id = profile.user_id WHERE user.username = '{$_SESSION["user"]["username"]}'");
$id = ($profile_id->fetch_assoc()['id']);

// Sanitize user inputs
$name = (!empty($_POST['job_title'])) ? trim($mysqli->real_escape_string($_POST['job_title'])) : null;
$start = $_POST['job_start'] ?? null;
$end = $_POST['job_end'] ?? null;
$employer = $_POST['employer'] ?? null;
$description = (!empty($_POST['job_description'])) ? trim($mysqli->real_escape_string($_POST['job_description'])) : null;

$type = is_null($end) ? 'current' : 'past';

$emp_stmt = $mysqli->prepare("
    SELECT id 
    FROM `employer` 
    WHERE name = ?
    ");

if (!$emp_stmt) {
    die('Prepare failed: ' . $mysqli->error);
}

// Bind parameter and execute the statement
$emp_stmt->bind_param('s', $employer);

if (!$emp_stmt->execute()) {
    die('Execute failed: ' . $emp_stmt->error);
}

// Fetch the result
$emp_result = $emp_stmt->get_result();
$emp_data = $emp_result->fetch_assoc();
$employer_id = $emp_data['id'] ?? null; // Fetching the employer id

// Close the language statement
$emp_stmt->close();

// Prepare the main statement for adding the job offer
$stmt = $mysqli->prepare("
    INSERT INTO job(employer_id, candidate_id, name, type, first_work_date, last_work_date, description) 
    VALUES (?, ?, ?, ?, ?, ?, ?)
    ");

if (!$stmt) {
    die('Prepare failed: ' . $mysqli->error);
}

$stmt->bind_param('iisssss', $employer_id, $id, $name, $type, $start, $end, $description);

// Execute the prepared statement
if (!$stmt->execute()) {
    die('Execute failed: ' . $stmt->error); // Log error for debugging
}

// Close the statement and connection
$stmt->close();

$mysqli->close();

header("Location: candidates_my_resume.php?message=Job added successfully");

exit();
