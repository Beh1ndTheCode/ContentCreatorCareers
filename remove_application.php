<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "include/config.inc.php";
require "include/dbms.inc.php";

$job_offer_id = filter_var($_GET['job_id'], FILTER_SANITIZE_NUMBER_INT);
$candidate_id = filter_var($_GET['can_id'], FILTER_SANITIZE_NUMBER_INT);
$type = filter_var($_GET['type'], FILTER_SANITIZE_NUMBER_INT);

$stmt = $mysqli->prepare("DELETE FROM application WHERE candidate_id = ? AND job_offer_id = ?");

if (!$stmt) {
    die('Prepared failed: ' . $mysqli->error);
}

$stmt->bind_param('ii', $candidate_id, $job_offer_id);

if (!$stmt->execute()) {
    die('Execute failed: ' . $stmt->error);
}

$stmt->close();
$mysqli->close();

if ($type == 2) {
    header("Location: candidates_applied_jobs.php?message=Application removed successfully");
} elseif ($type == 3) {
    header("Location: employer_resume.php?message=Application removed successfully");
} else {
    error_log("Unknown source");
}

exit();
