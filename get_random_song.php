<?php
// Initialize an empty array for songs
include "includes/config.php";
$songs = [];

// Get the number of songs currently in the queue from the request
$songQueueCount = isset($_GET['queue_count']) ? intval($_GET['queue_count']) : 0;

// Ensure the queue count is not more than 10
if ($songQueueCount >= 10) {
    echo json_encode([]); // If the queue already has 10 or more songs, return an empty array
    exit;
}

// Calculate how many random songs to load
$remainingSongsToLoad = 10 - $songQueueCount; // Max queue size is 10

// Fetch the remaining number of random songs
$randomSongsQuery = $pdo->prepare("
    SELECT
        s.song_id,
        s.song_name,
        s.song_pic_url AS image,
        s.song_url AS url,
        u.name AS artist_name
    FROM songs s
    LEFT JOIN users u ON s.user_id = u.user_id
    ORDER BY RAND()
    LIMIT :remaining
");

// Check if the query preparation was successful
if (!$randomSongsQuery) {
    echo json_encode(["error" => $pdo->errorInfo()]);
    exit;
}

// Bind the number of remaining songs to load
$randomSongsQuery->bindParam(':remaining', $remainingSongsToLoad, PDO::PARAM_INT);

// Execute the query and fetch the results
if ($randomSongsQuery->execute()) {
    $songs = $randomSongsQuery->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo json_encode(["error" => $randomSongsQuery->errorInfo()]);
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
