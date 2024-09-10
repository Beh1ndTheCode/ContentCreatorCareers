<?php
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "include/config.inc.php";
require "include/dbms.inc.php";

$profileResult = $mysqli->query("SELECT profile.id FROM `profile` JOIN `user` ON user.id = profile.user_id WHERE user.username = '{$_SESSION["user"]["username"]}'");
$profile_id = ($profileResult->fetch_assoc()['id']);
$source = $_POST['source'];

// Sanitize user inputs
$facebook = (!empty($_POST['facebook'])) ? trim($mysqli->real_escape_string($_POST['facebook'])) : null;
$instagram = (!empty($_POST['instagram'])) ? trim($mysqli->real_escape_string($_POST['instagram'])) : null;
$website = (!empty($_POST['website'])) ? trim($mysqli->real_escape_string($_POST['website'])) : null;
$linkedin = (!empty($_POST['linkedin'])) ? trim($mysqli->real_escape_string($_POST['linkedin'])) : null;
$phone_num = (!empty($_POST['phone_num'])) ? trim($mysqli->real_escape_string($_POST['phone_num'])) : null;
$email = (!empty($_POST['email'])) ? trim($mysqli->real_escape_string($_POST['email'])) : null;
$country = (!empty($_POST['country'])) ? trim($mysqli->real_escape_string($_POST['country'])) : null;
$city = (!empty($_POST['city'])) ? trim($mysqli->real_escape_string($_POST['city'])) : null;

$postcode = $street = $civic = null;

if ($source == 'employer') {
    $postcode = (!empty($_POST['postcode'])) ? intval($_POST['postcode']) : null;
    $street = (!empty($_POST['street'])) ? trim($mysqli->real_escape_string($_POST['street'])) : null;
    $civic = (!empty($_POST['civic'])) ? trim($mysqli->real_escape_string($_POST['civic'])) : null;
}

// AGGIUNGERE UPDATE DEI PROFILI SOCIAL
$stmt = $mysqli->prepare("
        UPDATE 
            profile
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
            profile.id = ?
    ");

if (!$stmt) {
    die('Prepare failed: ' . $mysqli->error);
}

$stmt->bind_param('ssssisss', $email, $phone_num, $country, $city, $postcode, $street, $civic, $profile_id);

// Execute the prepared statement
if (!$stmt->execute()) {
    error_log('Execute failed: ' . $stmt->error); // Log error for debugging
}

// Close the statement and connection
$stmt->close();

$social_accounts = ['facebook' => $facebook, 'instagram' => $instagram, 'linkedin' => $linkedin, 'website' => $website,];

foreach ($social_accounts as $name => $uri) {
    if ($uri) {
        // Check if the social account already exists for this profile
        $stmt = $mysqli->prepare("SELECT 1 FROM social_account WHERE profile_id = ? AND name = ?");
        if (!$stmt) {
            die('Prepare failed: ' . $mysqli->error);
        }
        $stmt->bind_param('is', $profile_id, $name);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // If record exists, update the URI
            $stmt->close();
            $updateStmt = $mysqli->prepare("UPDATE social_account SET uri = ? WHERE profile_id = ? AND name = ?");
            if (!$updateStmt) {
                die('Prepare failed: ' . $mysqli->error);
            }
            $updateStmt->bind_param('sis', $uri, $profile_id, $name);
            if (!$updateStmt->execute()) {
                error_log('Update failed: ' . $updateStmt->error);
            }
            $updateStmt->close();
        } else {
            // If record does not exist, insert new record
            $stmt->close();
            $insertStmt = $mysqli->prepare("INSERT INTO social_account (profile_id, name, uri) VALUES (?, ?, ?)");
            if (!$insertStmt) {
                die('Prepare failed: ' . $mysqli->error);
            }
            $insertStmt->bind_param('iss', $profile_id, $name, $uri);
            if (!$insertStmt->execute()) {
                error_log('Insert failed: ' . $insertStmt->error);
            }
            $insertStmt->close();
        }
    }
}

$mysqli->close();

// Redirect based on the source page after update
if ($source == 'candidate') {
    header("Location: candidates_profile.php?message=Contact info updated successfully");
} elseif ($source == 'employer') {
    header("Location: employer_profile.php?message=Contact info updated successfully");
} else {
    error_log("Unknown source page");
}

exit();
