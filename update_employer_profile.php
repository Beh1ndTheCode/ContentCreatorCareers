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
$since = (!empty($_POST['since'])) ? intval($_POST['since']) : null;
$description = (!empty($_POST['description'])) ? trim($mysqli->real_escape_string($_POST['description'])) : null;

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

$stmt->bind_param('siss', $name, $since, $description, $username);


// Execute the prepared statement
if (!$stmt->execute()) {
    error_log('Execute failed: ' . $stmt->error); // Log error for debugging
}

// Close the statement and connection
$stmt->close();
$mysqli->close();

// Redirect to the profile page after update
header("Location: employer_profile.php");

exit();
