<?php
session_start();
if (!isset($_SESSION["admin_username"])) {
    header("Location: login.php");
    exit();
}

include "../includes/config.php";

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

        header("Location: fetchAllsongs.php");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
