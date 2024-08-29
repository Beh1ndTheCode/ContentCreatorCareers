<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

# $config['auth'] = "true";

require "include/config.inc.php";
require "include/dbms.inc.php";
require "include/template2.inc.php";
require "include/get_by_id.inc.php";

// Verifica se l'utente è loggato
if (isset($_SESSION['user'])) {
    // Utente loggato
    $profile_id = $mysqli->query("SELECT profile.id FROM `profile` JOIN `user` ON user.id = profile.user_id WHERE user.username = '{$_SESSION["user"]["username"]}'");
    $id = ($profile_id->fetch_assoc()['id']);
    $user_type = $mysqli->query("
        SELECT 'candidates' AS user_type 
        FROM candidate 
        WHERE id = $id 
        UNION 
        SELECT 'employer' AS user_type 
        FROM employer 
        WHERE id = $id
    ");
    $user_type = $user_type->fetch_assoc()['user_type'];
    $username = $mysqli->query("
        SELECT user.username AS username
        FROM user
        JOIN profile ON profile.user_id = user.id
        WHERE profile.id = $id
    ")->fetch_assoc()['username'];
    $img = get_img($mysqli,$id);
    $data = [
        'logged_in' => true,
        'type' => $user_type,
        'img' => $img,
        'username' => $username
    ];
} else {
    // Utente non loggato
    $data = [
        'logged_in' => false
    ];
}
echo json_encode($data);
?>