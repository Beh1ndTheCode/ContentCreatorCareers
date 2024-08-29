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
$facebook = $mysqli->real_escape_string($_POST['facebook']);
$instagram = $mysqli->real_escape_string($_POST['instagram']);
$website = $mysqli->real_escape_string($_POST['website']);
$linkedin = $mysqli->real_escape_string($_POST['linkedin']);
$phone_num = $mysqli->real_escape_string($_POST['phone_num']);
$email = $mysqli->real_escape_string($_POST['email']);
$country = $mysqli->real_escape_string($_POST['country']);
$city = $mysqli->real_escape_string($_POST['city']);
$postcode = $mysqli->real_escape_string($_POST['postcode']);
$street = $mysqli->real_escape_string($_POST['street']);
$civic = $mysqli->real_escape_string($_POST['civic']);

// SQL query to update the user's profile
// AGGIUNGERE UPDATE DEGLI ACCOUNT SOCIAL
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
