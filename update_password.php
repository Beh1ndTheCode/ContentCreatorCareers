<?php

session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "include/config.inc.php";
require "include/dbms.inc.php";

$username = $mysqli->real_escape_string($_SESSION['user']['username']);
$source = $_POST['source'];

// Sanitize user inputs
$old_password = (!empty($_POST['old_password'])) ? $mysqli->real_escape_string($_POST['old_password']) : null;
$new_password = (!empty($_POST['new_password'])) ? $mysqli->real_escape_string($_POST['new_password']) : null;
$confirm_new_password = (!empty($_POST['confirm_new_password'])) ? $mysqli->real_escape_string($_POST['confirm_new_password']) : null;

if ($new_password !== $confirm_new_password) {
    $result = 0;
    echo json_encode($result);
    exit();
}

// SQL query to update the user's profile
$stmt = $mysqli->prepare("
    UPDATE user
    SET user.password = ?
    WHERE user.username = ? AND user.password = ?
");

if (!$stmt) {
    die('Prepare failed: ' . $mysqli->error);
}

$stmt->bind_param('sss', $new_password, $username, $old_password);
$stmt->execute();

if ($stmt->affected_rows === 0) {
    $result = 1; // Old password didn't match
} else {
    if ($source == 'candidate') {
        $result = 2;
    } elseif ($source == 'employer') {
        $result = 3;
    } else {
        error_log("Unknown source page");
    }
}

echo json_encode($result);

// Close the statement and connection
$stmt->close();
$mysqli->close();

exit();
