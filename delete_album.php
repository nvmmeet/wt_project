<?php
include "includes/config.php";
include "includes/sessions/false.php";

if (isset($_POST['delete_album'])) {
    $albumId = intval($_POST['album_id']);

    $pdo->beginTransaction();

    try {
        $stmt = $pdo->prepare("DELETE FROM album_songs WHERE album_id = :album_id");
        $stmt->execute(['album_id' => $albumId]);

        $stmt = $pdo->prepare("
            DELETE FROM songs 
            WHERE song_id IN (SELECT song_id FROM album_songs WHERE album_id = :album_id)
        ");
        $stmt->execute(['album_id' => $albumId]);

        $stmt = $pdo->prepare("
            DELETE FROM playlist_songs 
            WHERE song_id IN (SELECT song_id FROM album_songs WHERE album_id = :album_id)
        ");
        $stmt->execute(['album_id' => $albumId]);

        $stmt = $pdo->prepare("DELETE FROM albums WHERE album_id = :album_id");
        $stmt->execute(['album_id' => $albumId]);

        $pdo->commit();

        echo '<script>window.history.go(-2);</script>';
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
