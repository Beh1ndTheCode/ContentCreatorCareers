<?php
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "include/config.inc.php";
require "include/dbms.inc.php";

$profile_id = $mysqli->query("SELECT profile.id FROM `profile` JOIN `user` ON user.id = profile.user_id WHERE user.username = '{$_SESSION["user"]["username"]}'");
$candidate_id = ($profile_id->fetch_assoc()['id']);

if (isset($_GET['id'])) {
    $job_offer_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
} else {
    die ("Invalid request, missing required parameters.");
}

$stmt = $mysqli->prepare("INSERT INTO application(candidate_id, job_offer_id) VALUES (?, ?)");

if (!$stmt) {
    die('Prepare failed: ' . $mysqli->error);
}

$stmt->bind_param('ii', $candidate_id, $job_offer_id);

// Execute the prepared statement
if (!$stmt->execute()) {
    die('Execute failed: ' . $stmt->error); // Log error for debugging
}

// Close the statement and connection
$stmt->close();

$mysqli->close();

header("Location: candidates_applied_jobs.php?message=Application added successfully");
exit();
