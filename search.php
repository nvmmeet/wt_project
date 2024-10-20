<?php
include "includes/config.php";

$query = isset($_GET['query']) ? $_GET['query'] : '';

if (empty($query)) {
    $albumsQuery = $pdo->query("SELECT album_id,album_name, album_pic_url FROM albums ORDER BY RAND() LIMIT 2");
    $artistsQuery = $pdo->query("SELECT user_id,username, profile_pic_url FROM users ORDER BY RAND() LIMIT 2");
} else {

    $albumsQuery = $pdo->prepare("SELECT album_id,album_name, album_pic_url 
                                  FROM albums 
                                  WHERE album_name LIKE :query 
                                  LIMIT 2");
    $albumsQuery->execute(['query' => "%$query%"]);

    $artistsQuery = $pdo->prepare("SELECT user_id,username, profile_pic_url 
                                   FROM users 
                                   WHERE username LIKE :query 
                                   LIMIT 2");
    $artistsQuery->execute(['query' => "%$query%"]);
}

$albums = $albumsQuery->fetchAll(PDO::FETCH_ASSOC);
$artists = $artistsQuery->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'albums' => $albums,
    'artists' => $artists,
]);
