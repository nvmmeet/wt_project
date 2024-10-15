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
        $errors[] = "Please provide an album name.";
    } else {
        $album_name = $_POST['album_name'];
    }

    $album_pic_name = 'emptyalbum.jpg';

    if (!empty($_FILES['album_image_upload']['name'])) {
        $album_pic_name = uploadFile('album_image_upload', 'uploads/images/albums/', $errors);

        if ($album_pic_name === 'emptyalbum.jpg') {
            $album_pic_name = 'default';
        }
    }

    if (empty($errors)) {
        $albumInsertQuery = $pdo->prepare("
            INSERT INTO albums (album_name, album_pic_url, user_id)
            VALUES (:album_name, :album_pic_name, :user_id)
        ");
        $albumInsertQuery->execute([
            'album_name' => $album_name,
            'album_pic_name' => $album_pic_name,
            'user_id' => $user_id,
        ]);

        header("Location: my-uploads.php");
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
