<?php
include "includes/config.php";
include "includes/sessions/false.php";

$username = $_SESSION['username'];

$userQuery = $pdo->prepare("SELECT user_id FROM users WHERE username = :username");
$userQuery->execute(['username' => $username]);
$user = $userQuery->fetch();
$user_id = $user['user_id'];

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['album_name'])) {
        $errors[] = "Please provide a playlist name.";
    } else {
        $playlist_name = $_POST['album_name'];
    }

    $playlist_pic_name = 'emptyplaylist.jpg';

    if (!empty($_FILES['playlist_image_upload']['name'])) {
        $playlist_pic_name = uploadFile('playlist_image_upload', 'uploads/images/playlists/', $errors);
    }

    if ($playlist_pic_name === 'emptyplaylist.jpg') {
        $playlist_pic_name = 'default';
    }

    if (empty($errors)) {
        $playlistInsertQuery = $pdo->prepare("
            INSERT INTO playlists (playlist_name, playlist_pic_url, user_id)
            VALUES (:playlist_name, :playlist_pic_url, :user_id)
        ");
        $playlistInsertQuery->execute([
            'playlist_name' => $playlist_name,
            'playlist_pic_url' => $playlist_pic_name,
            'user_id' => $user_id,
        ]);

        header("Location: playlists.php");
        exit();
    }
}

function uploadFile($inputName, $uploadDir, &$errors)
{
    $fileName = basename($_FILES[$inputName]['name']);
    $targetFile = $uploadDir . $fileName;
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    $counter = 1;
    while (file_exists($targetFile)) {
        $fileNameWithoutExt = pathinfo($fileName, PATHINFO_FILENAME);
        $fileName = $fileNameWithoutExt . "_" . $counter . "." . $fileType;
        $targetFile = $uploadDir . $fileName;
        $counter++;
    }

    if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $targetFile)) {
        return $fileName;
    } else {
        $errors[] = "There was an error uploading the file.";
        return null;
    }
}
