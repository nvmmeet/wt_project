<?php
session_start();

include "includes/config.php";
include "includes/sessions/false.php";

if (isset($_POST['delete_song'])) {
    $songId = intval($_POST['song_id']);

    $pdo->beginTransaction();

    try {
        $stmt = $pdo->prepare("DELETE FROM album_songs WHERE song_id = :song_id");
        $stmt->execute(['song_id' => $songId]);

        $stmt = $pdo->prepare("DELETE FROM playlist_songs WHERE song_id = :song_id");
        $stmt->execute(['song_id' => $songId]);

        $stmt = $pdo->prepare("DELETE FROM fav_songs WHERE song_id = :song_id");
        $stmt->execute(['song_id' => $songId]);

        $stmt = $pdo->prepare("DELETE FROM songs WHERE song_id = :song_id");
        $stmt->execute(['song_id' => $songId]);

        $pdo->commit();

        if (isset($_SERVER['HTTP_REFERER'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        } else {
            header('Location: index.php');
            exit;
        }
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
