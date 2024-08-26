<?php

session_start();

require "include/config.inc.php";
require "include/dbms.inc.php";
require "include/template2.inc.php";
require "include/auth.inc.php";
require "include/get_by_id.inc.php";

$main = new Template("frame");
$body = new Template("employer_profile");

$profile_id = $mysqli->query("SELECT profile.id FROM `profile` JOIN `user` ON user.id = profile.user_id WHERE user.username = '{$_SESSION["user"]["username"]}'");
$profile_id = ($profile_id->fetch_assoc()['id']);

$img = get_img($mysqli, $profile_id);
$body->setContent("image", $img);

$result = $mysqli->query("
    SELECT
        employer.name AS name,
        profile.phone AS phone_num,
        profile.email AS email,
        profile.description AS description,
        address.country AS country,
        address.city AS city,
        address.postal_code AS postal_code,
        address.street AS street,
        address.civic AS civic
    FROM employer 
    JOIN profile ON profile.id = employer.id
    JOIN address ON address.profile_id = profile.id
    WHERE profile.id = '$profile_id'
    ");

$data = $result->fetch_assoc();
$body->setContent("name", $data['name']);
$body->setContent("phone_num", $data['phone_num']);
$body->setContent("email", $data['email']);
$body->setContent("country", $data['country']);
$body->setContent("city", $data['city']);
$body->setContent("postcode", $data['postal_code']);
$body->setContent("street", $data['street']);
$body->setContent("civic", $data['civic']);
$body->setContent("description", $data['description']);

$socials = get_socials($mysqli, $profile_id);
foreach ($socials as $social) $body->setContent($social["name"], $social["uri"]);

$main->setContent("body", $body->get());

$main->close();
