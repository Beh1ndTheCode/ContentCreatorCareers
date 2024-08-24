<?php

require "include/config.inc.php";
require "include/dbms.inc.php";


if ($_POST['type'] == "can") {
    $stmt = $mysqli->prepare("CALL AddCandidate(?, ?, ?, ?, ?, @new_user_id, @new_profile_id)");
    $stmt->bind_param('sssss', $_POST['username'], $_POST['password'], $_POST['email'], $_POST['name'], $_POST['surname']);
} elseif ($_POST['type'] == "emp") {
    $stmt = $mysqli->prepare("CALL AddEmployer(?, ?, ?, ?, @new_user_id, @new_profile_id)");
    $stmt->bind_param('ssss', $_POST['username'], $_POST['password'], $_POST['email'], $_POST['name']);
}

// Execute the prepared statement
if (!$stmt->execute()) {
    $result = 0;
} else {
    $result = 1;
}

echo json_encode($result);

// Close the statement and connection
$stmt->close();
$mysqli->close();
