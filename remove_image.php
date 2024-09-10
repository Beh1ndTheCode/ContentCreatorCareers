<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "include/config.inc.php";
require "include/dbms.inc.php";

$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

// Fetch the photo path from the database before deleting the record
$select_stmt = $mysqli->prepare("SELECT path FROM `image` WHERE id = ?");
if (!$select_stmt) {
    die('Prepared failed: ' . $mysqli->error);
}

$select_stmt->bind_param('i', $id);

if (!$select_stmt->execute()) {
    die('Execute failed: ' . $select_stmt->error);
}

// Get the result and fetch the path
$select_stmt->bind_result($path);
$select_stmt->fetch();
$select_stmt->close();

// Delete the file from the server if the path exists
if ($path && file_exists($path)) {
    if (!unlink($path)) {
        die('Failed to delete the file: ' . $path);
    }
}

// Now proceed to delete the record from the database
$delete_stmt = $mysqli->prepare("DELETE FROM `image` WHERE id = ?");
if (!$delete_stmt) {
    die('Prepared failed: ' . $mysqli->error);
}

$delete_stmt->bind_param('i', $id);

if (!$delete_stmt->execute()) {
    die('Execute failed: ' . $delete_stmt->error);
}

$delete_stmt->close();
$mysqli->close();

header("Location: candidates_my_resume.php?message=Image removed successfully");
exit();
