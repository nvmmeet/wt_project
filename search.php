<?php
include "includes/config.php";

$query = isset($_GET['query']) ? $_GET['query'] : '';

if (empty($query)) {
    $songsQuery = $pdo->query("SELECT songs.song_id,songs.song_name, songs.song_pic_url, users.username 
                               FROM songs 
                               JOIN users ON songs.user_id = users.user_id 
                               ORDER BY RAND() 
                               LIMIT 2");
    $albumsQuery = $pdo->query("SELECT album_id,album_name, album_pic_url FROM albums ORDER BY RAND() LIMIT 2");
    $artistsQuery = $pdo->query("SELECT user_id,username, profile_pic_url FROM users ORDER BY RAND() LIMIT 2");
} else {
    $songsQuery = $pdo->prepare("SELECT songs.song_id,songs.song_name, songs.song_pic_url, users.username 
                                 FROM songs 
                                 JOIN users ON songs.user_id = users.user_id 
                                 WHERE songs.song_name LIKE :query 
                                 LIMIT 2");
    $songsQuery->execute(['query' => "%$query%"]);

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

$songs = $songsQuery->fetchAll(PDO::FETCH_ASSOC);
$albums = $albumsQuery->fetchAll(PDO::FETCH_ASSOC);
$artists = $artistsQuery->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'songs' => $songs,
    'albums' => $albums,
    'artists' => $artists,
]);
