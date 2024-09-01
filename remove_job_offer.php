<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "include/config.inc.php";
require "include/dbms.inc.php";

$id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

$stmt = $mysqli->prepare("DELETE FROM job_offer WHERE job_offer.id = ?");

if (!$stmt) {
    die('Prepared failed: ' . $mysqli->error);
}

$stmt->bind_param('i', $id);

if (!$stmt->execute()) {
    die('Execute failed: ' . $stmt->error);
}

$stmt->close();
$mysqli->close();

header("Location: employer_manage_jobs.php");
exit();
