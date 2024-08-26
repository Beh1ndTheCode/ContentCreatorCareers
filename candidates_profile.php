<?php

session_start();

require "include/config.inc.php";
require "include/dbms.inc.php";
require "include/template2.inc.php";
require "include/auth.inc.php";
require "include/get_by_id.inc.php";

$main = new Template("frame");
$body = new Template("candidates_profile");

$profile_id = $mysqli->query("SELECT profile.id FROM `profile` JOIN `user` ON user.id = profile.user_id WHERE user.username = '{$_SESSION["user"]["username"]}'");
$profile_id = ($profile_id->fetch_assoc()['id']);

$img = get_img($mysqli,$profile_id);
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
    WHERE profile.id = '$profile_id'
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


$usr_language = $mysqli->query("SELECT language.name FROM `language` JOIN `candidate` ON candidate.language_id = language.id WHERE candidate.id = '$profile_id'");
$usr_language = $usr_language->fetch_assoc()["name"];
$sql_languages = $mysqli->query("SELECT language.name FROM `language`");
while ($row = $sql_languages->fetch_assoc())
    $languages[] = $row['name'];
$languages_html = '';
foreach ($languages as $language) {
    if ($language == $usr_language)
        $languages_html .= "<option selected>{$language}</option>";
    else
        $languages_html .= "<option >{$language}</option>";
}
$body->setContent("languages", $languages_html);


$socials = get_socials($mysqli,$profile_id);
foreach($socials as $social)
    $body->setContent($social["name"],$social["uri"]);


$main->setContent("body", $body->get());

$main->close();
