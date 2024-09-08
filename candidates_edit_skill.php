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
$body = new Template("candidates_edit_skill");

// Fetch profile ID
$profile_stmt = $mysqli->prepare("
    SELECT profile.id 
    FROM `profile` 
    JOIN `user` ON user.id = profile.user_id 
    WHERE user.username = ?
");
$profile_stmt->bind_param('s', $_SESSION["user"]["username"]);
$profile_stmt->execute();
$profile_id_result = $profile_stmt->get_result();
$id = $profile_id_result->fetch_assoc()['id'];
$profile_stmt->close();

$body->setContent("can_id", $id);

// Sanitize and bind skill_name parameter
$skill_name = filter_var($_GET['skill_name'], FILTER_SANITIZE_STRING);
$body->setContent("old_skill_name", $skill_name);

// Fetch candidate details
$candidate_stmt = $mysqli->prepare("
    SELECT name, surname
    FROM `candidate`
    WHERE id = ?
");
$candidate_stmt->bind_param('i', $id);
$candidate_stmt->execute();
$candidate_result = $candidate_stmt->get_result();
$data = $candidate_result->fetch_assoc();
$body->setContent("name", $data['name']);
$body->setContent("surname", $data['surname']);
$candidate_stmt->close();

// Fetch skill details
$skill_stmt = $mysqli->prepare("
    SELECT name, level, description
    FROM `skill`
    WHERE candidate_id = ? AND name = ?
");
$skill_stmt->bind_param('is', $id, $skill_name);
$skill_stmt->execute();
$skill_result = $skill_stmt->get_result();

if ($skill_result->num_rows === 0) {
    die('Skill not found.');
}

$data = $skill_result->fetch_assoc();
$body->setContent("skill_name", $data['name']);
$body->setContent("skill_level", $data['level']);
$body->setContent("skill_description", $data['description']);
$skill_stmt->close();

$main->setContent("body", $body->get());
$main->close();
