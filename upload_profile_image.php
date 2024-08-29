<?php

session_start();

require "include/config.inc.php";
require "include/dbms.inc.php";
require "include/auth.inc.php";

$target_dir = "uploads/profile_images/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Check if the file is an actual image
$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
if($check !== false) {
    echo "File is an image - " . $check["mime"] . ".<br>";
    $uploadOk = 1;
} else {
    echo "File is not an image.<br>";
    $uploadOk = 0;
}

// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.<br>";
    $uploadOk = 0;
}

// Check file size (1MB limit)
if ($_FILES["fileToUpload"]["size"] > 1048576) {
    echo "Sorry, your file is too large.<br>";
    $uploadOk = 0;
}

// Allow only certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
    echo "Sorry, only JPG, JPEG, & PNG files are allowed.<br>";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.<br>";
// if everything is ok, try to upload the file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename($_FILES["fileToUpload"]["name"]). " has been uploaded.<br>";

        // Save file path to the database
        $username = $mysqli->real_escape_string($_SESSION['user']['username']);
        echo "Username from session: $username<br>";

        $query = "
            UPDATE image 
            JOIN employer ON employer.id = image.profile_id
            JOIN user ON user.id = employer.user_id
            SET image.path = '$target_file'
            SET image.type = 'profilo'
            WHERE user.username = '$username' AND image.type = 'profilo'
        ";
        echo "Executing query: $query<br>";

        $result = $mysqli->query($query);

        if (!$result) {
            echo "Database update failed: " . $mysqli->error . "<br>";
        } else {
            echo "Database update successful.<br>";
            header("Location: employer_profile.php");
        }

    } else {
        echo "Sorry, there was an error uploading your file.<br>";
    }
}

// Debugging: Display all information about the uploaded file
echo "<pre>";
print_r($_FILES);
echo "</pre>";

