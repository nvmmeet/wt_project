<?php
include "includes/sessions/false.php";
include "includes/config.php";
$artist_id = $_GET['id'];
if (!$artist_id) {
    exit("BAD REQUEST!");
} else {
    $username = $_SESSION["username"];
    $userGet = $pdo->prepare("SELECT user_id FROM users WHERE username = :username");
    $userGet->execute(["username" => $username]);
    $user_id = $userGet->fetch()["user_id"];

    $followQuery = $pdo->prepare("INSERT INTO social(followed_id,following_id) VALUES(:artist_id,:user_id)");
    $followQuery->execute(["artist_id" => $artist_id, "user_id" => $user_id]);
    if (isset($_SERVER['HTTP_REFERER'])) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    } else {
        header('Location: index.php');
        exit;
    }
}
