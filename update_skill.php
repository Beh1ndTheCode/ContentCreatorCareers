<?php

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "include/config.inc.php";
require "include/dbms.inc.php";

$can_id = intval($_POST['can_id']);
$old_name = $_POST['old_skill_name'];

// Sanitize user inputs
$name = (!empty($_POST['skill_name'])) ? trim($mysqli->real_escape_string($_POST['skill_name'])) : null;
$level = (!empty($_POST['skill_level'])) ? intval($_POST['skill_level']) : null;
$description = (!empty($_POST['skill_description'])) ? trim($mysqli->real_escape_string($_POST['skill_description'])) : null;

// SQL query to update the job
$stmt = $mysqli->prepare("
    UPDATE `skill`
    SET name = ?, level = ?, description = ?
    WHERE candidate_id = ? AND name = ?
");

if (!$stmt) {
    die('Prepare failed: ' . $mysqli->error);
}

$stmt->bind_param('sisis', $name, $level, $description, $can_id, $old_name);

if (!$stmt->execute()) {
    die('Execute failed: ' . $stmt->error);
}

// Close the statement and connection
$stmt->close();
$mysqli->close();

header("Location: candidates_my_resume.php");

exit();
