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
$body = new Template("candidates_edit_job");

$profile_id = $mysqli->query("SELECT profile.id FROM `profile` JOIN `user` ON user.id = profile.user_id WHERE user.username = '{$_SESSION["user"]["username"]}'");
$id = ($profile_id->fetch_assoc()['id']);

$job_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
$body->setContent("job_id", $job_id);

$result = $mysqli->query("
    SELECT name, surname
    FROM `candidate`
    WHERE id = $id
    ");
$data = $result->fetch_assoc();
$body->setContent("name", $data['name']);
$body->setContent("surname", $data['surname']);

$job = $mysqli->query("
    SELECT
        name,
        first_work_date AS start,
        last_work_date AS end,
        employer_id AS emp_id,
        description
    FROM `job`
    WHERE job.id = $job_id
");

if (!$job) {
    die('Query failed: ' . $mysqli->error);
}
$data = $job->fetch_assoc();
$body->setContent("job_title", $data['name']);
$body->setContent("job_start", $data['start']);
$body->setContent("job_end", $data['end']);
$body->setContent("job_description", $data['description']);

$emp_id = $data['emp_id'];
$job_employer = $mysqli->query("SELECT name FROM `employer` WHERE id = $emp_id");
$employers_sql = $mysqli->query("SELECT name FROM `employer` ORDER BY name");
if ($employers_sql) {
    $employers_html = '';
    $selected_employer = $job_employer;
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

