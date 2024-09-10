<?php

session_start();

require "include/config.inc.php";
require "include/dbms.inc.php";

$profileResult = $mysqli->query("SELECT profile.id FROM `profile` JOIN `user` ON user.id = profile.user_id WHERE user.username = '{$_SESSION["user"]["username"]}'");
$profile_id = ($profileResult->fetch_assoc()['id']);

// Define target directory for uploads
$target_dir = "uploads/portfolios/";
$uploadOk = 1;

// Extract the file extension and create a unique filename
$imageFileType = strtolower(pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION));
$target_file = $target_dir . uniqid() . '.' . $imageFileType;

// Check if the file is an actual image
$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
if ($check !== false) {
    $uploadOk = 1;
} else {
    echo "File is not an image.<br>";
    $uploadOk = 0;
}

// Check file size (10MB limit)
if ($_FILES["fileToUpload"]["size"] > 10485760) {
    echo "Sorry, your file is too large.<br>";
    $uploadOk = 0;
}

// Allow only certain file formats
if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
    echo "Sorry, only JPG, JPEG, & PNG files are allowed.<br>";
    $uploadOk = 0;
}

// Check if the directory exists and is writable
if (!file_exists($target_dir)) {
    echo "Error: Directory $target_dir does not exist.<br>";
    $uploadOk = 0;
} elseif (!is_writable($target_dir)) {
    echo "Error: Directory $target_dir is not writable.<br>";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.<br>";
} else {
    // Attempt to move the uploaded file temporarily
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        // Insert query to store the image path in the database
        $query = "
            INSERT INTO image(profile_id, type, path) 
            VALUES ($profile_id, 'portfolio', '$target_file')
        ";
        $result = $mysqli->query($query);

        if (!$result) {
            echo "Database update failed: " . $mysqli->error . "<br>";
        } else {
            header("Location: candidates_my_resume.php?message=Porfolio updated successfully");
            exit(); // Use exit to stop further script execution after redirect
        }

    } else {
        // Detailed error message for troubleshooting
        echo "Sorry, there was an error uploading your file.<br>";
        echo "Possible causes: insufficient permissions, incorrect path, file system errors.<br>";
    }
}