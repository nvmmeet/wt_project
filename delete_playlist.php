<?php
session_start();

include "includes/config.php";

if (isset($_POST['delete_playlist'])) {
    $playlistId = intval($_POST['playlist_id']);

    $pdo->beginTransaction();

    try {
        $stmt = $pdo->prepare("DELETE FROM playlist_songs WHERE playlist_id = :playlist_id");
        $stmt->execute(['playlist_id' => $playlistId]);

        $stmt = $pdo->prepare("DELETE FROM playlists WHERE playlist_id = :playlist_id");
        $stmt->execute(['playlist_id' => $playlistId]);

        $pdo->commit();

        header("Location: playlists.php");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
