<?php

require "include/config.inc.php";
require "include/dbms.inc.php";

# $passwd = md5("{$_POST['password']}");
# $result = $mysqli->query("SELECT username, email FROM `user` WHERE username = '{$_POST['username']}' AND password = '{$passwd}'");

$stmt = $mysqli->prepare("SELECT username, email FROM `user` WHERE username = ? AND password = ?");

// Bind the parameters to the placeholders
$stmt->bind_param("ss", $_POST['username'], $_POST['password']);

// Execute the statement
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Check if the user exists
if ($result->num_rows == 1) {
    $result = 1; // User exists
} else {
    $result = 0; // User does not exist
}

echo json_encode($result);

// Close the statement and connection
$stmt->close();
$mysqli->close();
