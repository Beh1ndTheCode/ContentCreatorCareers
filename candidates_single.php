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
$body = new Template("candidates_single");

//verifica presenza immagine profilo
$profile_id = $mysqli->query("SELECT profile.id FROM `profile` JOIN `user` ON user.id = profile.user_id WHERE user.username = '{$_SESSION["user"]["username"]}'");
$profile_id = ($profile_id->fetch_assoc()['id']);
$body->setContent("profile_id", $profile_id);

$img = $mysqli->query("SELECT image.path FROM `image` WHERE image.profile_id = '$profile_id'");

//$img = a immagine profilo se esiste, altrimenti è impostata su un placeholder
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

$sql_skills=$mysqli->query("SELECT skill.name FROM `skill` WHERE skill.candidate_id = '$profile_id'");
while($row = $sql_skills->fetch_assoc()) {
    $skills[] = $row['name']; // Aggiungi ogni skill all'array
}
// Costruisci il contenuto HTML per le skills
$skills_html = '';
foreach ($skills as $skill) {
    $skills_html .= "<span>{$skill}</span>";
}
$body->setContent("skills", $skills_html);

$main->setContent("body", $body->get());
$main->close();

?>