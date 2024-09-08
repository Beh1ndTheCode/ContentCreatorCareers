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
$body = new Template("employer_change_password");

$profile_id = $mysqli->query("SELECT profile.id FROM `profile` JOIN `user` ON user.id = profile.user_id WHERE user.username = '{$_SESSION["user"]["username"]}'");
$id = ($profile_id->fetch_assoc()['id']);

$result = $mysqli->query("
    SELECT employer.name AS name
    FROM employer
    WHERE employer.id = '$id'
    ");

$data = $result->fetch_assoc();
$body->setContent("name", $data['name']);

$main->setContent("body", $body->get());

$main->close();
