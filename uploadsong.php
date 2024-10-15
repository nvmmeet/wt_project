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
    if (empty($_FILES['song_url']['name'])) {
        $errors[] = "Please upload a music file.";
    }

    if (empty($_POST['song_name'])) {
        $errors[] = "Please provide a song name.";
    }

    $song_pic_name = 'default';

    if (!empty($_FILES['song_image_upload']['name'])) {
        $uploaded_pic_name = uploadFile('song_image_upload', 'uploads/images/songs/', $errors);
        if ($uploaded_pic_name === 'emptysong.jpg') {
            $song_pic_name = 'default';
        } else {
            $song_pic_name = $uploaded_pic_name;
        }
    }

    if (empty($errors)) {
        $song_url = uploadFile('song_url', 'uploads/songs/', $errors);

        if (empty($errors)) {
            $song_name = $_POST['song_name'];
            $song_album = $_POST['song_album'] !== 'none' ? $_POST['song_album'] : null;

            $songInsertQuery = $pdo->prepare("
                INSERT INTO songs (song_name, song_url, song_pic_url, user_id)
                VALUES (:song_name, :song_url, :song_pic_url, :user_id)
            ");
            $songInsertQuery->execute([
                'song_name' => $song_name,
                'song_url' => $song_url,
                'song_pic_url' => $song_pic_name,
                'user_id' => $user_id,
            ]);

            if ($song_album) {
                $song_id = $pdo->lastInsertId();
                $albumSongQuery = $pdo->prepare("
                    INSERT INTO album_songs (album_id, song_id) 
                    VALUES (:album_id, :song_id)
                ");
                $albumSongQuery->execute([
                    'album_id' => $song_album,
                    'song_id' => $song_id,
                ]);
            }

            header("Location: my-uploads.php");
            exit();
        }
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
