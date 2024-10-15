<?php
include "includes/config.php";

// Get the album ID from the request
$albumId = isset($_GET['album']) ? intval($_GET['album']) : 0;

// Initialize an empty array for songs
$songs = [];

// Fetch songs for the specified album
$albumSongsQuery = $pdo->prepare("
    SELECT
        s.song_id,
        s.song_name,
        s.song_pic_url AS image,
        s.song_url AS url,
        u.name AS artist_name
    FROM album_songs AS asg
    JOIN songs AS s ON asg.song_id = s.song_id
    JOIN users AS u ON s.user_id = u.user_id
    WHERE asg.album_id = :album_id
");

if (!$albumSongsQuery) {
    echo json_encode(["error" => $pdo->errorInfo()]);
    exit;
}

// Bind the album ID
$albumSongsQuery->bindParam(':album_id', $albumId, PDO::PARAM_INT);

// Execute the query and fetch the results
if ($albumSongsQuery->execute()) {
    $songs = $albumSongsQuery->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo json_encode(["error" => $albumSongsQuery->errorInfo()]);
    exit;
}

// Modify the song URLs and image paths
foreach ($songs as &$song) {
    $song['url'] = 'uploads/songs/' . $song['url'];
    $song['image'] = $song['image'] !== 'default'
        ? 'uploads/images/songs/' . $song['image']
        : 'uploads/images/songs/emptysong.jpg';
}

// Return the songs in JSON format
header('Content-Type: application/json');
echo json_encode($songs);
