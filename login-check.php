<?php

require "include/config.inc.php";
require "include/dbms.inc.php";

# $passwd = md5("{$_POST['password']}");
# $result = $mysqli->query("SELECT username, email FROM `user` WHERE username = '{$_POST['username']}' AND password = '{$passwd}'");

$stmt = $mysqli->prepare("
    SELECT user.username, user.email, user_role.role_id
    FROM `user`
    JOIN `user_role` ON user_role.username = user.username
    WHERE user.username = ? AND user.password = ?
    ");

// Bind the parameters to the placeholders
$stmt->bind_param("ss", $_POST['username'], $_POST['password']);

// Execute the statement
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Check if the user exists
if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    if (isset($_SESSION['user']['username']) && $row['username'] != $_SESSION['user']['username']) {
        session_start();
        session_unset();
        session_destroy();
        setcookie(session_name(), '', time() - 3600);
    }
    session_set_cookie_params(1800);
    session_start();
    session_regenerate_id();
    $result = $row['role_id']; // Return user role id
} else {
    $result = 0; // User does not exist
}

echo json_encode($result);

// Close the statement and connection
$stmt->close();
$mysqli->close();
