<?php

require "include/config.inc.php";
require "include/dbms.inc.php";

// Prepare the statements to avoid SQL injection
$stmt_username = $mysqli->prepare("SELECT username FROM `user` WHERE username = ?");
$stmt_email = $mysqli->prepare("SELECT email FROM `user` WHERE email = ?");

// Bind parameters and execute for username
$stmt_username->bind_param("s", $_POST['username']);
$stmt_username->execute();
$stmt_username->store_result();
$username_exists = $stmt_username->num_rows > 0;

// Bind parameters and execute for email
$stmt_email->bind_param("s", $_POST['email']);
$stmt_email->execute();
$stmt_email->store_result();
$email_exists = $stmt_email->num_rows > 0;

// Determine the result
if ($username_exists && $email_exists) {
    $result = 0;
} elseif ($username_exists) {
    $result = 1;
} elseif ($email_exists) {
    $result = 2;
} else {
    $result = 3;
}


echo json_encode($result);

$stmt_username->close();
$stmt_email->close();
$mysqli->close();
