<?php
include "includes/config.php"; // Include your database connection
include "includes/sessions/false.php"; // Handle sessions if needed

$username = $_SESSION['username']; // Get logged-in username

// Fetch user_id based on the username
$userQuery = $pdo->prepare("SELECT user_id FROM users WHERE username = :username");
$userQuery->execute(['username' => $username]);
$user = $userQuery->fetch();
$user_id = $user['user_id'];

$errors = []; // Initialize an array to hold any errors

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate playlist name
    if (empty($_POST['album_name'])) {
        $errors[] = "Please provide a playlist name.";
    } else {
        $playlist_name = $_POST['album_name']; // Use the album_name field for playlist name
    }

    // Default playlist image name
    $playlist_pic_name = 'emptyplaylist.jpg'; // Default image name

    // Handle playlist image upload
    if (!empty($_FILES['playlist_image_upload']['name'])) {
        $playlist_pic_name = uploadFile('playlist_image_upload', 'uploads/images/playlists/', $errors);
    }

    // If no errors, set 'default' if the image name is emptyplaylist.jpg
    if ($playlist_pic_name === 'emptyplaylist.jpg') {
        $playlist_pic_name = 'default'; // Set to 'default' for the database
    }

    // If there are no errors, insert into the database
    if (empty($errors)) {
        // Insert playlist details into the database
        $playlistInsertQuery = $pdo->prepare("
            INSERT INTO playlists (playlist_name, playlist_pic_url, user_id)
            VALUES (:playlist_name, :playlist_pic_url, :user_id)
        ");
        $playlistInsertQuery->execute([
            'playlist_name' => $playlist_name,
            'playlist_pic_url' => $playlist_pic_name, // Store only the image name
            'user_id' => $user_id,
        ]);

        // Redirect to another page or show success message
        header("Location: playlists.php");
        exit();
    }
}

// Function to upload files and return only the file name
function uploadFile($inputName, $uploadDir, &$errors)
{
    // Get the file name
    $fileName = basename($_FILES[$inputName]['name']);
    $targetFile = $uploadDir . $fileName;
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check for duplicate file names
    $counter = 1;
    while (file_exists($targetFile)) {
        $fileNameWithoutExt = pathinfo($fileName, PATHINFO_FILENAME);
        $fileName = $fileNameWithoutExt . "_" . $counter . "." . $fileType;
        $targetFile = $uploadDir . $fileName;
        $counter++;
    }

    // Move uploaded file
    if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $targetFile)) {
        return $fileName; // Return only the file name
    } else {
        $errors[] = "There was an error uploading the file.";
        return null;
    }
}
