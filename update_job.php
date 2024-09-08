<?php

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "include/config.inc.php";
require "include/dbms.inc.php";

$job_id = $_POST['job_id'];

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

// SQL query to update the job
$stmt = $mysqli->prepare("
    UPDATE `job`
    SET 
        employer_id = ?,
        name = ?,
        type = ?,
        first_work_date = ?,
        last_work_date = ?,
        description = ?
    WHERE id = ?
");

if (!$stmt) {
    die('Prepare failed: ' . $mysqli->error);
}

$stmt->bind_param('isssssi', $employer_id, $name, $type, $start, $end, $description, $job_id);

if (!$stmt->execute()) {
    die('Execute failed: ' . $stmt->error);
}

// Close the statement and connection
$stmt->close();
$mysqli->close();

header("Location: candidates_my_resume.php");

exit();
