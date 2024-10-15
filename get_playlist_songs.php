<?php
include "includes/config.php";

// Get the playlist ID from the request
$playlistId = isset($_GET['playlist']) ? intval($_GET['playlist']) : 0;

// Initialize an empty array for songs
$songs = [];

// Fetch songs for the specified playlist
$playlistSongsQuery = $pdo->prepare("
    SELECT
        s.song_id,
        s.song_name,
        s.song_pic_url AS image,
        s.song_url AS url,
        u.name AS artist_name
    FROM playlist_songs AS ps
    JOIN songs AS s ON ps.song_id = s.song_id
    JOIN users AS u ON s.user_id = u.user_id
    WHERE ps.playlist_id = :playlist_id
");

if (!$playlistSongsQuery) {
    echo json_encode(["error" => $pdo->errorInfo()]);
    exit;
}

// Bind the playlist ID
$playlistSongsQuery->bindParam(':playlist_id', $playlistId, PDO::PARAM_INT);

// Execute the query and fetch the results
if ($playlistSongsQuery->execute()) {
    $songs = $playlistSongsQuery->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo json_encode(["error" => $playlistSongsQuery->errorInfo()]);
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
