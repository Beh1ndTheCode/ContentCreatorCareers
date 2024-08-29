<?php

session_start();

require "include/config.inc.php";
require "include/dbms.inc.php";
require "include/template2.inc.php";
require "include/auth.inc.php";

$main = new Template("frame");
$body = new Template("employer_profile");

$username = $mysqli->real_escape_string($_SESSION['user']['username']);

$result = $mysqli->query("
    SELECT
        profile.id AS id,
        employer.name AS name,
        employer.since AS since,
        profile.phone AS phone_num,
        profile.email AS email,
        profile.description AS description,
        address.country AS country,
        address.city AS city,
        address.postal_code AS postal_code,
        address.street AS street,
        address.civic AS civic,
        image.path AS emp_image
    FROM 
        profile 
    JOIN 
        employer ON profile.id = employer.id
    JOIN 
        user ON user.id = profile.user_id
    LEFT JOIN 
        address ON address.profile_id = profile.id
    LEFT JOIN
        image ON image.profile_id = employer.id AND image.type = 'profilo'
    WHERE 
        user.username = '$username'
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
$body->setContent("since", $data['since']);
$body->setContent("phone_num", $data['phone_num']);
$body->setContent("email", $data['email']);
$body->setContent("country", $data['country']);
$body->setContent("city", $data['city']);
$body->setContent("postcode", $data['postal_code']);
$body->setContent("street", $data['street']);
$body->setContent("civic", $data['civic']);
$body->setContent("description", $data['description']);
$body->setContent("image", $image);

$socials = $mysqli->query("
    SELECT
        social_account.name AS social_name,
        social_account.uri AS social_uri
    FROM
        social_account
    JOIN
        employer ON employer.id = social_account.profile_id
    WHERE
        employer.id = '{$data['id']}'
");

if (!$socials) {
    die("Query failed: " . $mysqli->error);
}

while ($social = $socials->fetch_assoc()) {
    $social_name = $social['social_name'];
    $social_uri = $social['social_uri'];
    $body->setContent($social_name, $social_uri);
}


$main->setContent("body", $body->get());

$main->close();
