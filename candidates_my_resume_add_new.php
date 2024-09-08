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
$body = new Template("candidates_my_resume_add_new");

$profile_id = $mysqli->query("SELECT profile.id FROM `profile` JOIN `user` ON user.id = profile.user_id WHERE user.username = '{$_SESSION["user"]["username"]}'");
$id = ($profile_id->fetch_assoc()['id']);

$result = $mysqli->query("
    SELECT
        candidate.name AS name,
        candidate.surname AS surname
    FROM candidate
    WHERE candidate.id = '$id'
    ");
$data = $result->fetch_assoc();
$body->setContent("name", $data['name']);
$body->setContent("surname", $data['surname']);

$employers_sql = $mysqli->query("SELECT name FROM employer ORDER BY name");
if ($employers_sql) {
    $employers_html = '';
    $selected_employer = '';
    while ($row = $employers_sql->fetch_assoc()) {
        $employer_name = htmlspecialchars($row['name']);
        $selected = ($employer_name == $selected_employer) ? 'selected' : '';
        $employers_html .= "<option $selected>$employer_name</option>";
    }

    $body->setContent("employers", $employers_html);
} else {
    error_log("SQL Error: " . $mysqli->error);
    $body->setContent("employers", '<option>No Employers found</option>');
}

$main->setContent("body", $body->get());

$main->close();

