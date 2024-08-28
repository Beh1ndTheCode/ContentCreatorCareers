<?php

session_start();

require "include/config.inc.php";
require "include/dbms.inc.php";
require "include/template2.inc.php";
require "include/auth.inc.php";

$main = new Template("frame");
$body = new Template("employer_profile");

$profile_id = $mysqli->query("SELECT profile.id FROM `profile` JOIN `user` ON user.id = profile.user_id WHERE user.username = '{$_SESSION["user"]["username"]}'");
$profile_id = ($profile_id->fetch_assoc()['id']);

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
        address.civic AS civic,
        image.path AS emp_image,
        social_account.name AS social_name,
        social_account.uri AS social_uri
    FROM 
        employer 
    JOIN 
        profile ON profile.id = employer.id
    LEFT JOIN 
        address ON address.profile_id = profile.id
    LEFT JOIN
        image ON image.profile_id = employer.id AND image.type = 'profilo'
    LEFT JOIN
        social_account ON employer.id = social_account.profile_id
    WHERE 
        profile.id = '$profile_id'
    ");

if (!$result) {
    die("Query failed: " . $mysqli->error);
}

if ($result->num_rows === 0) {
    die("Employer not found.");
}

$data = $result->fetch_assoc();
$image = $data['emp_image'] ?? 'skins/jobhunt/images/profile.png';

$body->setContent("name", $data['name']);
$body->setContent("phone_num", $data['phone_num']);
$body->setContent("email", $data['email']);
$body->setContent("country", $data['country']);
$body->setContent("city", $data['city']);
$body->setContent("postcode", $data['postal_code']);
$body->setContent("street", $data['street']);
$body->setContent("civic", $data['civic']);
$body->setContent("description", $data['description']);
$body->setContent("image", $image);
$body->setContent($data['social_name'], $data['social_uri']);
$body->setContent("image", $image);

$main->setContent("body", $body->get());

$main->close();
