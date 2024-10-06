<?php
include "includes/sessions/false.php";
include "includes/config.php";

$song_id = $_GET['song'];
$playlist_id = $_GET['playlist'];
if (!$song_id) {
    exit("INCORRECT REQUEST!");
} else {
    $addToFavQuery = $pdo->prepare("INSERT INTO playlist_songs(playlist_id,song_id) VALUES(:playlist_id,:song_id)");
    $addToFavQuery->execute(["playlist_id" => $playlist_id, "song_id" => $song_id]);
    if (isset($_SERVER['HTTP_REFERER'])) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    } else {
        header('Location: index.php');
        exit;
    }
}
