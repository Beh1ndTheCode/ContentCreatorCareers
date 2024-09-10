<?php
session_start();

require "include/config.inc.php";
require "include/dbms.inc.php";

$number = $_POST['number'];
$page = $_POST['page'];

// Define target directory for uploads
$target_dir = "uploads/content_images/";
$estensioni = ['jpg', 'jpeg', 'png'];

// Cerca il file con una delle estensioni all'interno della cartella
foreach ($estensioni as $estensione) {
    $target_file = $target_dir . 'page' . $page . '_' . $number . '.' . $estensione;

    print_r($target_file);
    // Se il file esiste, lo eliminiamo
    if (file_exists($target_file)) {

        $query = "
            UPDATE content 
            JOIN service ON service.id = content.service_id
            SET content.$number = ''
            WHERE service.id = $page
        ";
        $result = $mysqli->query($query);

        if (!$result) {
            echo "Database update failed: " . $mysqli->error . "<br>";
            die();
        }
        unlink($target_file);
    }
}

header("Location: content_menagment.php?message=Image removed successfully");
exit(); // Use exit to stop further script execution after redirect