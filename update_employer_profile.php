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
$name = $mysqli->real_escape_string($_POST['name']);
$since = $mysqli->real_escape_string($_POST['since']);
$description = $mysqli->real_escape_string($_POST['description']);

// SQL query to update the user's profile
$stmt = $mysqli->prepare("
    UPDATE 
        employer
    JOIN
        profile ON profile.id = employer.id
    JOIN
        user ON user.id = profile.user_id
    SET
        employer.name = ?,
        employer.since = ?,
        profile.description = ?
    WHERE
        user.username = ?
");
if (!$stmt) {
    die('Prepare failed: ' . $mysqli->error);
}

$stmt->bind_param('ssss', $name, $since, $description, $username);


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
header("Location: employer_profile.php");
exit();
