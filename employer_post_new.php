<?php

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "include/config.inc.php";
require "include/dbms.inc.php";
require "include/template2.inc.php";
require "include/auth.inc.php";

$main = new Template("frame");
$body = new Template("employer_post_new");

$username = $mysqli->real_escape_string($_SESSION['user']['username']);

$result = $mysqli->query("
    SELECT
        employer.name AS name
    FROM
        employer
    JOIN 
        profile ON employer.id = profile.id
    JOIN
        user ON user.id = profile.user_id AND user.username = '$username'
    ");

if (!$result) {
    die("Query failed: " . $mysqli->error);
}

if ($result->num_rows === 0) {
    die("Employer not found.");
}

$data = $result->fetch_assoc();
$body->setContent("name", $data['name']);

$sql_languages = $mysqli->query("SELECT language.name FROM `language`");
while ($row = $sql_languages->fetch_assoc())
    $languages[] = $row['name'];
$languages_html = '';
foreach ($languages as $language) {
    $languages_html .= "<option>$language</option>";
}
$body->setContent("languages", $languages_html);

$main->setContent("body", $body->get());

$main->close();

