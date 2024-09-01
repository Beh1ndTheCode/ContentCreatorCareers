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
$name = (!empty($_POST['name'])) ? trim($mysqli->real_escape_string($_POST['name'])) : null;
$description = (!empty($_POST['description'])) ? trim($mysqli->real_escape_string($_POST['description'])) : null;
$job_type = $_POST['job_type'] ?? null;
$language = $_POST['language'] ?? null;
$salary = (!empty($_POST['salary'])) ? floatval($_POST['salary']) : null;
$quantity = (!empty($_POST['quantity'])) ? intval($_POST['quantity']) : null;

$lang_stmt = $mysqli->prepare("
    SELECT language.id AS lang
    FROM language
    WHERE language.name = ?
");

if (!$lang_stmt) {
    die('Prepare failed: ' . $mysqli->error);
}

// Bind parameter and execute the statement
$lang_stmt->bind_param('s', $language);

if (!$lang_stmt->execute()) {
    die('Execute failed: ' . $lang_stmt->error);
}

// Fetch the result
$lang_result = $lang_stmt->get_result();
$lang_data = $lang_result->fetch_assoc();
$language_id = $lang_data['lang'] ?? null; // Fetching the language id

// Close the language statement
$lang_stmt->close();

// Prepare the main statement for adding the job offer
$stmt = $mysqli->prepare("CALL AddJobOffer(?, ?, ?, ?, ?, ?, ?, @job_offer_id)");

if (!$stmt) {
    die('Prepare failed: ' . $mysqli->error);
}

$stmt->bind_param('isssiis', $id, $name, $salary, $job_type, $language_id, $quantity, $description);

// Execute the prepared statement
if (!$stmt->execute()) {
    die('Execute failed: ' . $stmt->error); // Log error for debugging
}

// Close the statement and connection
$stmt->close();

$result = $mysqli->query("SELECT @job_offer_id AS job_offer_id");

if (!$result) {
    die('Failed to retrieve Job Offer ID: ' . $mysqli->error);
}

$row = $result->fetch_assoc();
$job_offer_id = $row['job_offer_id'];

$mysqli->close();

if ($job_offer_id) {
// Redirect to next step
    header("Location: employer_add_requirements.php?id=" . urlencode($job_offer_id));
} else {
    die('Failed');
}
exit();
