<?php

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "include/config.inc.php";
require "include/dbms.inc.php";
require "include/template2.inc.php";
require "include/auth.inc.php";

$job_offer_id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

$main = new Template("frame");
$body = new Template("employer_add_requirements");

$body->setContent("job_offer_id", $job_offer_id);

$main->setContent("body", $body->get());

$main->close();

