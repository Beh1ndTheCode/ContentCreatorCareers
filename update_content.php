<?php


session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "include/config.inc.php";
require "include/dbms.inc.php";

// Sanitize user inputs
$id = ($_POST['id'] != null) ? trim($mysqli->real_escape_string($_POST['id'])) : null;
$name = ($_POST['name'] != null) ? trim($mysqli->real_escape_string($_POST['name'])) : null;

$subtitle1 = ($_POST['subtitle_1'] != null) ? trim($mysqli->real_escape_string($_POST['subtitle_1'])) : null;
$text1 = ($_POST['text_1'] != null) ? trim($mysqli->real_escape_string($_POST['text_1'])) : null;

$subtitle2 = ($_POST['subtitle_2'] != null) ? trim($mysqli->real_escape_string($_POST['subtitle_2'])) : null;
$text2 = ($_POST['text_2'] != null) ? trim($mysqli->real_escape_string($_POST['text_2'])) : null;

$subtitle3 = ($_POST['subtitle_3'] != null) ? trim($mysqli->real_escape_string($_POST['subtitle_3'])) : null;
$text3 = ($_POST['text_3'] != null) ? trim($mysqli->real_escape_string($_POST['text_3'])) : null;

$subtitle4 = ($_POST['subtitle_4'] != null) ? trim($mysqli->real_escape_string($_POST['subtitle_4'])) : null;
$text4 = ($_POST['text_4'] != null) ? trim($mysqli->real_escape_string($_POST['text_4'])) : null;

$stmt = $mysqli->prepare("
    UPDATE content
    JOIN service ON service.id = content.service_id
    SET service.name = ?,
        content.sottotitolo1 = ?,
        content.testo1 = ?,
        content.sottotitolo2 = ?,
        content.testo2 = ?,
        content.sottotitolo3 = ?,
        content.testo3 = ?,
        content.sottotitolo4 = ?,
        content.testo4 = ?
    WHERE service.id = ?
");

$stmt->bind_param('sssssssssi', $name, $subtitle1, $text1, $subtitle2, $text2, $subtitle3, $text3, $subtitle4, $text4, $id);

// Execute the prepared statement
if (!$stmt->execute()) {
    error_log('Execute failed: ' . $stmt->error); // Log error for debugging
}

// Close the statement and connection
$stmt->close();
$mysqli->close();

// Redirect to the content menagment page after update
header("Location: content_menagment.php");

exit();