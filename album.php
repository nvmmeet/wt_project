<?php
include "includes/sessions/false.php";
include "includes/config.php";

$username = $_SESSION["username"];
$album_id = intval($_GET['album']);

if (!$album_id) {
    $error = "Bad Request!";
}

// Fetch album and song details
$albumQuery = $pdo->prepare("
    SELECT 
        a.album_id,
        a.album_name,
        a.album_pic_url,
        s.song_id,
        s.song_name,
        s.song_pic_url,
        u.name AS artist_name
    FROM 
        albums a
    LEFT JOIN 
        album_songs als ON a.album_id = als.album_id
    LEFT JOIN 
        songs s ON als.song_id = s.song_id
    LEFT JOIN 
        users u ON s.user_id = u.user_id
    WHERE 
        a.album_id = :album_id
");
$albumQuery->execute(["album_id" => $album_id]);
$albumData = $albumQuery->fetchAll(PDO::FETCH_ASSOC);

if (empty($albumData)) {
    $error = "Album Not Found!";
} else {
    $album = reset($albumData);
}

// Fetch user's playlists for the "Add to Playlist" feature
$playlistQuery = $pdo->prepare("
    SELECT playlist_id, playlist_name 
    FROM playlists 
    WHERE user_id = (SELECT user_id FROM users WHERE username = :username)
");
$playlistQuery->execute(['username' => $username]);
$playlists = $playlistQuery->fetchAll();
?>

<html>

<head>
    <title><?= htmlspecialchars($album['album_name']) ?> | Beatwaves</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/sidebar.css" />
    <link rel="stylesheet" href="css/main.css" />
    <link rel="stylesheet" href="css/cards.css" />
</head>

<body>
    <?php include "includes/sidebar.php"; ?>
    <main>
        <?php if (isset($error)): ?>
            <h1 class="error"><?= htmlspecialchars($error) ?></h1>
        <?php else: ?>
            <section class="album-section">
                <div class="album-header">
                    <img src="<?= htmlspecialchars($album['album_pic_url']) ?>" alt="Album Cover" style="border-radius: 20px;" />
                    <h2><?= htmlspecialchars($album['album_name']) ?></h2>
                    <a href="#" class="play-button"><i class="bi bi-play-circle-fill"></i> Play</a>
                </div>

                <h2>Songs</h2>
                <div class="song-cards">
                    <?php foreach ($albumData as $song): ?>
                        <?php if ($song['song_id']): ?>
                            <?php
                            $songImage = $song['song_pic_url'] !== 'default'
                                ? 'uploads/images/songs/' . htmlspecialchars($song['song_pic_url'])
                                : 'uploads/images/songs/emptysong.jpg';
                            ?>
                            <div class='song-card'>
                                <img src='<?= $songImage ?>' alt='song' />
                                <span><?= htmlspecialchars($song['song_name']) ?></span>
                                <p><?= htmlspecialchars($song['artist_name']) ?></p>
                                <div class='play-button'>
                                    <i class='bi bi-caret-right-fill'></i>
                                </div>
                                <input type='checkbox' id='dropdown<?= $song['song_id'] ?>' />
                                <label for='dropdown<?= $song['song_id'] ?>'><i class='bi bi-three-dots-vertical'></i></label>
                                <div class='song-card-dropdown'>
                                    <div class='dropdown-item sub-dropdown hov'>
                                        Add to Playlist
                                        <div class='sub-dropdown-content'>
                                            <?php foreach ($playlists as $playlist): ?>
                                                <!-- Check if song is already in the playlist -->
                                                <div class='dropdown-item'>
                                                    <a href='addtopl.php?song=<?= $song['song_id'] ?>&playlist=<?= $playlist['playlist_id'] ?>'>
                                                        <?= htmlspecialchars($playlist['playlist_name']) ?>
                                                    </a>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <div class='dropdown-item'>
                                        <a href='addtofav.php?song=<?= $song['song_id'] ?>'>
                                            Add to Favourites
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>
    </main>
    <?php include "includes/searchbar.php" ?>
</body>

</html>