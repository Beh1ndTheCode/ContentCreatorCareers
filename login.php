<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

# $config['auth'] = "true";


require "include/config.inc.php";
require "include/dbms.inc.php";
require "include/template2.inc.php";

$main = new Template("frame");
$body = new Template("login");

$main->setContent("body", $body->get());

$main->close();
