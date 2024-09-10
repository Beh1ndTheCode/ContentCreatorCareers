<?php

session_start();

require "include/config.inc.php";
require "include/dbms.inc.php";

$profileResult = $mysqli->query("SELECT profile.id FROM `profile` JOIN `user` ON user.id = profile.user_id WHERE user.username = '{$_SESSION["user"]["username"]}'");
$profile_id = ($profileResult->fetch_assoc()['id']);
$source = $_POST['source'];

// Define target directory for uploads
$target_dir = "uploads/profile_images/";
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

// Check file size (1MB limit)
if ($_FILES["fileToUpload"]["size"] > 1048576) {
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
        // Resize the image
        $resized_file = $target_dir . 'resized_' . uniqid() . '.' . $imageFileType;
        resizeImage($target_file, $resized_file, $imageFileType);

        // Delete the original uploaded file after resizing
        unlink($target_file);

        // Fetch the current image path from the database
        $currentImageQuery = "SELECT path FROM image WHERE profile_id = $profile_id AND type = 'profilo'";
        $currentImageResult = $mysqli->query($currentImageQuery);
        if ($currentImageResult && $currentImageRow = $currentImageResult->fetch_assoc()) {
            $currentImagePath = $currentImageRow['path'];

            // Delete the old profile image file if it exists
            if (file_exists($currentImagePath)) {
                unlink($currentImagePath);
            }
        }

        // Update query to store the image path in the database
        $query = "
            UPDATE image 
            SET path = '$resized_file'
            WHERE profile_id = $profile_id AND type = 'profilo'
        ";
        $result = $mysqli->query($query);

        if (!$result) {
            echo "Database update failed: " . $mysqli->error . "<br>";
        } else {
            // Redirect based on source
            if ($source == 'candidate') {
                header("Location: candidates_profile.php?message=Profile image uploaded successfully");
            } elseif ($source == 'employer') {
                header("Location: employer_profile.php?message=Profile image uploaded successfully");
            } else {
                error_log("Unknown source page");
            }
            exit(); // Use exit to stop further script execution after redirect
        }

    } else {
        // Detailed error message for troubleshooting
        echo "Sorry, there was an error uploading your file.<br>";
        echo "Possible causes: insufficient permissions, incorrect path, file system errors.<br>";
    }
}

// Function to resize the image to a square
function resizeImage($source_path, $dest_path, $image_type)
{
    // Get the original dimensions
    list($width, $height) = getimagesize($source_path);

    // Calculate the new side length based on the minor side
    $new_side = min($width, $height);

    // Create a new true color image with the calculated side length
    $resized_image = imagecreatetruecolor($new_side, $new_side);

    // Load the source image based on its type
    switch ($image_type) {
        case 'jpg':
        case 'jpeg':
            $source_image = imagecreatefromjpeg($source_path);
            break;
        case 'png':
            $source_image = imagecreatefrompng($source_path);
            // Maintain PNG transparency
            imagealphablending($resized_image, false);
            imagesavealpha($resized_image, true);
            break;
        default:
            echo "Unsupported image type.<br>";
            return false;
    }

    // Calculate x and y positions to crop the center
    $src_x = ($width - $new_side) / 2;
    $src_y = ($height - $new_side) / 2;

    // Copy and resize the source image into the resized image
    imagecopyresampled($resized_image, $source_image, 0, 0, $src_x, $src_y, $new_side, $new_side, $new_side, $new_side);

    // Save the resized image
    switch ($image_type) {
        case 'jpg':
        case 'jpeg':
            imagejpeg($resized_image, $dest_path, 90); // Save as JPEG
            break;
        case 'png':
            imagepng($resized_image, $dest_path, 9); // Save as PNG
            break;
    }

    // Free memory
    imagedestroy($source_image);
    imagedestroy($resized_image);
}
