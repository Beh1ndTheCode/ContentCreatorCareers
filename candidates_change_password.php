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
$body = new Template("candidates_change_password");

$result = $mysqli->query("
    SELECT candidate.name, candidate.surname 
    FROM `candidate` 
    JOIN `profile` ON candidate.id = profile.id 
    JOIN `user` ON user.id = profile.user_id AND user.username = '{$_SESSION["user"]["username"]}'
    ");

$data = $result->fetch_assoc();
$body->setContent("name", $data['name']);
$body->setContent("surname", $data['surname']);

$main->setContent("body", $body->get());

$main->close();
