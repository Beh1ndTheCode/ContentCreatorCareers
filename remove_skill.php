<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "include/config.inc.php";
require "include/dbms.inc.php";

$candidate_id = filter_var($_GET['can_id'], FILTER_SANITIZE_NUMBER_INT);
$skill_name = filter_var($_GET['skill_name'], FILTER_SANITIZE_STRING);

$stmt = $mysqli->prepare("DELETE FROM skill WHERE candidate_id = ? AND name = ?");

if (!$stmt) {
    die('Prepared failed: ' . $mysqli->error);
}

$stmt->bind_param('is', $candidate_id, $skill_name);

if (!$stmt->execute()) {
    die('Execute failed: ' . $stmt->error);
}

$stmt->close();
$mysqli->close();

header("Location: candidates_my_resume.php");
exit();
