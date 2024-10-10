<?php
session_start();
if (!isset($_SESSION["admin_username"])) {
    header("Location: login.php");
    exit();
}

include "../includes/config.php";

if (isset($_POST['delete_user'])) {
    $userId = intval($_POST['user_id']);

    $pdo->beginTransaction();

    try {
        $pdo->prepare("DELETE FROM social WHERE followed_id = :user_id OR following_id = :user_id")->execute(['user_id' => $userId]);

        $songQuery = $pdo->prepare("SELECT song_id FROM songs WHERE user_id = :user_id");
        $songQuery->execute(['user_id' => $userId]);
        $songs = $songQuery->fetchAll(PDO::FETCH_COLUMN);

        if ($songs) {
            $pdo->prepare("DELETE FROM fav_songs WHERE song_id IN (" . implode(',', $songs) . ")")->execute();
        }

        if ($songs) {
            $pdo->prepare("DELETE FROM album_songs WHERE song_id IN (" . implode(',', $songs) . ")")->execute();
        }

        if ($songs) {
            $pdo->prepare("DELETE FROM playlist_songs WHERE song_id IN (" . implode(',', $songs) . ")")->execute();
        }

        if ($songs) {
            $pdo->prepare("DELETE FROM songs WHERE user_id = :user_id")->execute(['user_id' => $userId]);
        }

        $albumQuery = $pdo->prepare("SELECT album_id FROM albums WHERE user_id = :user_id");
        $albumQuery->execute(['user_id' => $userId]);
        $albums = $albumQuery->fetchAll(PDO::FETCH_COLUMN);

        if ($albums) {
            $pdo->prepare("DELETE FROM album_songs WHERE album_id IN (" . implode(',', $albums) . ")")->execute();
        }

        if ($albums) {
            $pdo->prepare("DELETE FROM albums WHERE user_id = :user_id")->execute(['user_id' => $userId]);
        }

        $playlistQuery = $pdo->prepare("SELECT playlist_id FROM playlists WHERE user_id = :user_id");
        $playlistQuery->execute(['user_id' => $userId]);
        $playlists = $playlistQuery->fetchAll(PDO::FETCH_COLUMN);

        if ($playlists) {
            $pdo->prepare("DELETE FROM playlist_songs WHERE playlist_id IN (" . implode(',', $playlists) . ")")->execute();
        }

        if ($playlists) {
            $pdo->prepare("DELETE FROM playlists WHERE user_id = :user_id")->execute(['user_id' => $userId]);
        }

        $pdo->prepare("DELETE FROM users WHERE user_id = :user_id")->execute(['user_id' => $userId]);

        $pdo->commit();

        header("Location: index.php");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
