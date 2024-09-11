<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "include/config.inc.php";
require "include/dbms.inc.php";

$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
$type = filter_var($_GET['type'], FILTER_SANITIZE_NUMBER_INT);

$stmt = $mysqli->prepare("DELETE FROM job WHERE job.id = ?");

if (!$stmt) {
    die('Prepared failed: ' . $mysqli->error);
}

$stmt->bind_param('i', $id);

if (!$stmt->execute()) {
    die('Execute failed: ' . $stmt->error);
}

$stmt->close();
$mysqli->close();

if ($type == 2) {
    header("Location: candidates_my_resume.php?message=Job removed successfully");
} elseif ($type == 3) {
    header("Location: employer_resume.php?message=Job removed successfully");
} else {
    error_log("Unknown source");
}

exit();
