<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "include/config.inc.php";
require "include/dbms.inc.php";
require "include/template2.inc.php";
require "include/auth.inc.php";
require "include/get_by_id.inc.php";

$main = new Template("frame");
$body = new Template("candidates_my_resume");

$profile_id = $mysqli->query("SELECT profile.id FROM `profile` JOIN `user` ON user.id = profile.user_id WHERE user.username = '{$_SESSION["user"]["username"]}'");
$profile_id = ($profile_id->fetch_assoc()['id']);

$result = $mysqli->query("
    SELECT
        candidate.name AS name,
        candidate.surname AS surname,
        candidate.age AS age
    FROM candidate
    ");

$data = $result->fetch_assoc();
$body->setContent("name", $data['name']);
$body->setContent("surname", $data['surname']);
$body->setContent("age", $data['age']);

$skills = get_skills($mysqli,$profile_id);
$skills_html = '';
foreach($skills as $skill){
    $skills_html .= "<div class='progress-sec with-edit'>
                        <span>{$skill['name']}</span>
                        <div class='progressbar'>
                            <div class='progress' style='width: {$skill['level']}0%;'><span>{$skill['level']}0%</span></div>
                        </div>
                        <ul class='action_job'>
                            <li><span>Edit</span><a href='#' title=''><i class='la la-pencil'></i></a></li>
                            <li><span>Delete</span><a href='#' title=''><i class='la la-trash-o'></i></a></li>
                        </ul>
                    </div>";
}
$body->setContent("skills",$skills_html);

$main->setContent("body", $body->get());

$main->close();

?>