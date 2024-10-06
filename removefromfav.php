<?php
include "includes/sessions/false.php";
include "includes/config.php";

$song_id = $_GET['song'];
$username = $_SESSION["username"];
if (!$song_id) {
    exit("INCORRECT REQUEST!");
} else {
    $userQueryF = $pdo->prepare("SELECT user_id FROM users WHERE username = :username");
    $userQueryF->execute(["username" => $username]);
    $userI = $userQueryF->fetch();

    $removeFromFav = $pdo->prepare("DELETE FROM fav_songs WHERE user_id = :user_id AND song_id = :song_id");
    $removeFromFav->execute(["user_id" => $userI["user_id"], "song_id" => $song_id]);
    if (isset($_SERVER['HTTP_REFERER'])) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    } else {
        header('Location: index.php');
        exit;
    }
}
