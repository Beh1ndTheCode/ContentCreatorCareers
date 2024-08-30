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
$name = isset($_POST['name']) ? $mysqli->real_escape_string($_POST['name']) : '';
$surname = isset($_POST['surname']) ? $mysqli->real_escape_string($_POST['surname']) : '';
$age = isset($_POST['age']) ? $mysqli->real_escape_string($_POST['age']) : '';
$description = isset($_POST['description']) ? $mysqli->real_escape_string($_POST['description']) : '';
$expertise = isset($_POST['expertise']) ? $mysqli->real_escape_string($_POST['job_title']) : '';
$experience = isset($_POST['experience']) ? $mysqli->real_escape_string($_POST['experience']) : '';

// SQL query to update the user's profile
$stmt = $mysqli->prepare("
    UPDATE 
        candidate
    JOIN
        profile ON profile.id = candidate.id
    JOIN
        user ON user.id = profile.user_id
    JOIN
        profile_expertise ON profile_expertise.profile_id = candidate.id
    JOIN 
        expertise ON expertise.id = profile_expertise.expertise_id
    SET
        candidate.name = ?,
        candidate.surname = ?,
        candidate.age = ?,
        profile.description = ?,
        profile_expertise.experience = ?,
        expertise.title = ?
    WHERE
        user.username = ?
");

if (!$stmt) {
    die('Prepare failed: ' . $mysqli->error);
}

$stmt->bind_param('sssssss', $name, $surname, $age, $description, $experience, $expertise, $username);


// Execute the prepared statement
if (!$stmt->execute()) {
    $result = 0;
    error_log('Execute failed: ' . $stmt->error); // Log error for debugging
} else {
    $result = 1;
}

echo json_encode($result);

// Close the statement and connection
$stmt->close();
$mysqli->close();

// Redirect to the profile page after update
header("Location: candidates_profile.php");
exit();
