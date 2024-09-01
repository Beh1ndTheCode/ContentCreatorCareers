<?php

session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "include/config.inc.php";
require "include/dbms.inc.php";

$username = $mysqli->real_escape_string($_SESSION['user']['username']);

// Sanitize user inputs
$name = (!empty($_POST['name'])) ? trim($mysqli->real_escape_string($_POST['name'])) : null;
$surname = (!empty($_POST['surname'])) ? trim($mysqli->real_escape_string($_POST['surname'])) : null;
$age = (!empty($_POST['age'])) ? intval($_POST['age']) : null;
$language = $_POST['language'] ?? null;
$description = (!empty($_POST['description'])) ? trim($mysqli->real_escape_string($_POST['description'])) : null;
$expertise = (!empty($_POST['job_title'])) ? trim($mysqli->real_escape_string($_POST['job_title'])) : null;
$experience = (!empty($_POST['experience'])) ? intval($_POST['experience']) : null;

// SQL query to update the user's profile
$stmt = $mysqli->prepare("
    UPDATE 
        candidate
    JOIN
        profile ON profile.id = candidate.id
    JOIN
        user ON user.id = profile.user_id
    JOIN
        language ON language.name = ?
    JOIN
        profile_expertise ON profile_expertise.profile_id = candidate.id
    JOIN 
        expertise ON expertise.id = profile_expertise.expertise_id
    SET
        candidate.name = ?,
        candidate.surname = ?,
        candidate.age = ?,
        candidate.language_id = language.id, 
        profile.description = ?,
        profile_expertise.experience = ?,
        expertise.title = ?
    WHERE
        user.username = ?
");

if (!$stmt) {
    die('Prepare failed: ' . $mysqli->error);
}

$stmt->bind_param('sssisiss', $language, $name, $surname, $age, $description, $experience, $expertise, $username);

// Execute the prepared statement
if (!$stmt->execute()) {
    error_log('Execute failed: ' . $stmt->error); // Log error for debugging
}

// Close the statement and connection
$stmt->close();
$mysqli->close();

// Redirect to the profile page after update
header("Location: candidates_profile.php");

exit();
