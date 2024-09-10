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
$name = (!empty($_POST['skill_name'])) ? trim($mysqli->real_escape_string($_POST['skill_name'])) : null;
$level = (!empty($_POST['skill_level'])) ? intval($_POST['skill_level']) : null;
$description = (!empty($_POST['skill_description'])) ? trim($mysqli->real_escape_string($_POST['skill_description'])) : null;


// Prepare the main statement for adding the job offer
$stmt = $mysqli->prepare("INSERT INTO skill(candidate_id, name, level, description) VALUES (?, ?, ?, ?)");

if (!$stmt) {
    die('Prepare failed: ' . $mysqli->error);
}

$stmt->bind_param('isis', $id, $name, $level, $description);

// Execute the prepared statement
if (!$stmt->execute()) {
    die('Execute failed: ' . $stmt->error); // Log error for debugging
}

// Close the statement and connection
$stmt->close();

$mysqli->close();

header("Location: candidates_my_resume.php?message=Skill added successfully");

exit();
