<?php

session_start();

require "include/config.inc.php";
require "include/dbms.inc.php";
require "include/template2.inc.php";
require "include/auth.inc.php";

$main = new Template("frame");
$body = new Template("candidates_profile");

$username = $_SESSION["user"]["username"];
$img = $mysqli->query("SELECT image.path FROM `image` JOIN `profile` ON profile.id = image.profile_id JOIN `user` ON user.id = profile.user_id WHERE user.username = '$username'");

if ($img->num_rows == 0) {
    $img = "skins/jobhunt/images/profile.png";
} else {
    $img = ($img->fetch_array())[0];
}
$body->setContent("image", $img);

$result = $mysqli->query("
    SELECT
        candidate.name AS name,
        candidate.surname AS surname,
        candidate.age AS age,
        expertise.title AS job_title,
        profile.phone AS phone_num,
        profile.email AS email,
        address.country AS country,
        address.city AS city
    FROM candidate 
    JOIN profile ON profile.id = candidate.id
    JOIN profile_expertise ON profile_expertise.profile_id = profile.id
    JOIN expertise ON expertise.id = profile_expertise.expertise_id
    JOIN address ON address.profile_id = profile.id
    JOIN user ON user.id = profile.user_id
    WHERE user.username = '$username'
    ");

$data = $result->fetch_assoc();
$body->setContent("name", $data['name']);
$body->setContent("surname", $data['surname']);
$body->setContent("age", $data['age']);
$body->setContent("job_title", $data['job_title']);
$body->setContent("phone_num", $data['phone_num']);
$body->setContent("email", $data['email']);
$body->setContent("country", $data['country']);
$body->setContent("city", $data['city']);



$main->setContent("body", $body->get());

$main->close();
