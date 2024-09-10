<?php

session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "include/config.inc.php";
require "include/dbms.inc.php";

$job_offer_id = $_POST['job_offer_id'];
$names = $_POST['requirement_name'];
$levels = $_POST['requirement_level'];
$descriptions = $_POST['requirement_description'];

// Check if arrays are set and process each skill set
if (is_array($names) && is_array($levels) && is_array($descriptions)) {
    for ($i = 0; $i < count($names); $i++) {
        // Sanitize user inputs
        $name = (!empty($names[$i])) ? trim($mysqli->real_escape_string($names[$i])) : null;
        $level = (!empty($levels[$i])) ? intval($levels[$i]) : null;
        $description = (!empty($descriptions[$i])) ? trim($mysqli->real_escape_string($descriptions[$i])) : null;

        // Prepare the main statement for adding the job offer
        $stmt = $mysqli->prepare("CALL AddRequirement(?, ?, ?, ?)");

        if (!$stmt) {
            die('Prepare failed: ' . $mysqli->error);
        }

        $stmt->bind_param('isis', $job_offer_id, $name, $level, $description);

        // Execute the prepared statement
        if (!$stmt->execute()) {
            die('Execute failed: ' . $stmt->error); // Log error for debugging
        }

        // Close the statement and connection
        $stmt->close();
    }
}

$mysqli->close();

// Redirect to next step
header("Location: employer_manage_jobs.php?message=requirements added successfully");

exit();
