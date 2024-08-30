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
$facebook = isset($_POST['facebook']) ? $mysqli->real_escape_string($_POST['facebook']) : '';
$instagram = isset($_POST['instagram']) ? $mysqli->real_escape_string($_POST['instagram']) : '';
$website = isset($_POST['website']) ? $mysqli->real_escape_string($_POST['website']) : '';
$linkedin = isset($_POST['linkedin']) ? $mysqli->real_escape_string($_POST['linkedin']) : '';
$phone_num = isset($_POST['phone_num']) ? $mysqli->real_escape_string($_POST['phone_num']) : '';
$email = isset($_POST['email']) ? $mysqli->real_escape_string($_POST['email']) : '';
$country = isset($_POST['country']) ? $mysqli->real_escape_string($_POST['country']) : '';
$city = isset($_POST['city']) ? $mysqli->real_escape_string($_POST['city']) : '';

$postcode = $street = $civic = '';

if ($source == 'employer') {
    $postcode = isset($_POST['postcode']) ? $mysqli->real_escape_string($_POST['postcode']) : '';
    $street = isset($_POST['street']) ? $mysqli->real_escape_string($_POST['street']) : '';
    $civic = isset($_POST['civic']) ? $mysqli->real_escape_string($_POST['civic']) : '';
}


// Prepare the SQL query with dynamic binding based on source
// AGGIUNGERE UPDATE DEI PROFILI SOCIAL
if ($source == 'employer') {
    $stmt = $mysqli->prepare("
        UPDATE 
            profile
        JOIN
            user ON user.id = profile.user_id
        JOIN
            address ON address.profile_id = profile.id
        SET
            profile.email = ?,
            profile.phone = ?,
            address.country = ?,
            address.city = ?,
            address.postal_code = ?,
            address.street = ?,
            address.civic = ?
        WHERE
            user.username = ?
    ");

    if (!$stmt) {
        die('Prepare failed: ' . $mysqli->error);
    }

    $stmt->bind_param('ssssssss', $email, $phone_num, $country, $city, $postcode, $street, $civic, $username);
} elseif ($source == 'candidate') {
    $stmt = $mysqli->prepare("
        UPDATE 
            profile
        JOIN
            user ON user.id = profile.user_id
        JOIN
            address ON address.profile_id = profile.id
        SET
            profile.email = ?,
            profile.phone = ?,
            address.country = ?,
            address.city = ?
        WHERE
            user.username = ?
    ");

    if (!$stmt) {
        die('Prepare failed: ' . $mysqli->error);
    }

    $stmt->bind_param('sssss', $email, $phone_num, $country, $city, $username);
}

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

// Redirect based on the source page after update
if ($source == 'candidate') {
    header("Location: candidates_profile.php");
} elseif ($source == 'employer') {
    header("Location: employer_profile.php");
} else {
    error_log("Unknown source page");
}
exit();
