<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "include/config.inc.php";
require "include/dbms.inc.php";
require "include/template2.inc.php";
require "include/get_by_id.inc.php";

// Verifica se l'utente è loggato
if (isset($_SESSION['user'])) {
    // Utente loggato
    $username = $_SESSION["user"]["username"];

    // Recupera il ruolo dell'utente
    $result = $mysqli->query("
        SELECT user_role.role_id AS role
        FROM user_role
        WHERE user_role.username = '$username'
    ");

    if ($result) {
        $user_type = $result->fetch_assoc()['role'];

        // Prepara i dati di base da restituire come JSON
        $data = [
            'logged_in' => true,
            'type' => $user_type,
            'username' => $username
        ];

        // Se user_type non è uguale a 3, cerca l'immagine del profilo
        if ($user_type != 1) {
            $profile_result = $mysqli->query("
                SELECT profile.id 
                FROM `profile` 
                JOIN `user` ON user.id = profile.user_id 
                WHERE user.username = '$username'
            ");

            if ($profile_result) {
                $id = $profile_result->fetch_assoc()['id'];

                // Ottieni l'immagine del profilo
                $img = get_img($mysqli, $id);
                $data['img'] = $img;
                
            } else {
                // Gestisci errore nel recuperare il profilo
                $data['error'] = 'Errore nel recupero del profilo';
            }
        }
    } else {
        // Gestisci errore nel recuperare il ruolo
        $data = ['error' => 'Errore nel recupero del ruolo'];
    }
} else {
    // Utente non loggato
    $data = [
        'logged_in' => false
    ];
}

// Stampa il JSON
header('Content-Type: application/json');
echo json_encode($data);