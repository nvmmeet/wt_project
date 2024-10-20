<?php
include "includes/sessions/false.php";
include "includes/config.php";

$username = $_SESSION["username"];
$playlist_id = intval($_GET['playlist']);

if (!$playlist_id) {
    $error = "Bad Request!";
}

$playlistQuery = $pdo->prepare("
    SELECT 
        p.playlist_id,
        p.playlist_name,
        p.playlist_pic_url,
        s.song_id,
        s.song_name,
        s.song_pic_url,
        s.song_url,
        u.name AS artist_name,
        IF(f.song_id IS NOT NULL, 1, 0) AS is_favorite
    FROM 
        playlists p
    LEFT JOIN 
        playlist_songs pls ON p.playlist_id = pls.playlist_id
    LEFT JOIN 
        songs s ON pls.song_id = s.song_id
    LEFT JOIN 
        users u ON s.user_id = u.user_id
    LEFT JOIN 
        fav_songs f ON f.song_id = s.song_id AND f.user_id = (SELECT user_id FROM users WHERE username = :username)
    WHERE 
        p.playlist_id = :playlist_id
");
$playlistQuery->execute(["playlist_id" => $playlist_id, "username" => $username]);
$playlistData = $playlistQuery->fetchAll(PDO::FETCH_ASSOC);

if (empty($playlistData)) {
    $error = "Playlist Not Found!";
} else {
    $playlist = reset($playlistData);
}

$playlistOptionsQuery = $pdo->prepare("
    SELECT playlist_id, playlist_name 
    FROM playlists 
    WHERE user_id = (SELECT user_id FROM users WHERE username = :username)
");
$playlistOptionsQuery->execute(['username' => $username]);
$playlists = $playlistOptionsQuery->fetchAll();
?>

<html>

<head>
    <title><?= htmlspecialchars($playlist['playlist_name']) ?> | Beatwaves</title>
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
        <div class="breadcrumb">
            <h1>Playlist</h1>
        </div>
        <?php if (isset($error)): ?>
            <h1 class="error"><?= htmlspecialchars($error) ?></h1>
        <?php else: ?>
            <section class="album-section">
                <div class="album-header">
                    <?php $playlistUrl = $playlist['playlist_pic_url'] === 'default' ? "uploads/images/playlists/emptyplaylist.jpg" : "uploads/images/playlists/" . $playlist["playlist_pic_url"]; ?>
                    <img src="<?= $playlistUrl ?>" alt="Playlist Cover" style="border-radius: 20px;" />
                    <h2><?= htmlspecialchars($playlist['playlist_name']) ?></h2>
                    <div class="actions">
                        <form method="post" action="delete_playlist.php">
                            <input type="hidden" name="playlist_id" value="<?= htmlspecialchars($playlist['playlist_id']) ?>" />
                            <button type="submit" class="delete" name="delete_playlist" onclick="return confirm('Are you sure you want to delete this album and all associated songs?');"><i class="bi bi-trash"></i></button>
                        </form>
                        <button type="submit" class="main-play-button play-button" data-playlist-id="<?= htmlspecialchars($playlist['playlist_id']) ?>" onclick="playPlaylist(<?= htmlspecialchars($playlist['playlist_id']) ?>)"><i class="bi bi-caret-right-fill"></i></button>
                    </div>
                </div>

                <div class="song-cards">
                    <?php foreach ($playlistData as $song): ?>
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
                                <div class='play-button' onclick="playSongFromCard(this)"
                                    data-song-id="<?= $song['song_id'] ?>"
                                    data-song-name="<?= htmlspecialchars($song['song_name']) ?>"
                                    data-song-image="<?= $songImage ?>"
                                    data-artist-name="<?= htmlspecialchars($song['artist_name']) ?>"
                                    data-song-url="uploads/songs/<?= $song['song_url'] ?>">
                                    <i class='bi bi-caret-right-fill'></i>
                                </div>
                                <input type='checkbox' id='dropdown<?= $song['song_id'] ?>' />
                                <label for='dropdown<?= $song['song_id'] ?>'><i class='bi bi-three-dots-vertical'></i></label>
                                <div class='song-card-dropdown'>
                                    <div class='dropdown-item'>
                                        <a href='removefrompl.php?song=<?= $song['song_id'] ?>&playlist=<?= $playlist['playlist_id'] ?>'>
                                            Remove from Playlist
                                        </a>
                                    </div>
                                    <div class='dropdown-item'>
                                        <?php if ($song['is_favorite']): ?>
                                            <a href='removefromfav.php?song=<?= $song['song_id'] ?>'>
                                                Unfavorite it!
                                            </a>
                                        <?php else: ?>
                                            <a href='addtofav.php?song=<?= $song['song_id'] ?>'>
                                                Favourite it!
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php endif; ?>
    </main>
    <?php include "song.php" ?>
    <?php include "includes/searchbar.php" ?>
    <script src="js/song.js"></script>
</body>

</html>