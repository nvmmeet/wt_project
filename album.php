<?php
include "includes/sessions/false.php";
include "includes/config.php";

$username = $_SESSION["username"];
$album_id = intval($_GET['album']);

if (!$album_id) {
    $error = "Bad Request!";
}

$albumQuery = $pdo->prepare("
    SELECT 
        a.album_id,
        a.album_name,
        a.album_pic_url,
        s.song_id,
        s.song_name,
        s.song_pic_url,
        s.song_url,
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
        <div class="breadcrumb">
            <h1>Album</h1>
        </div>
        <?php if (isset($error)): ?>
            <h1 class="error"><?= htmlspecialchars($error) ?></h1>
        <?php else: ?>
            <section class="album-section">
                <div class="album-header">
                    <?php $url = $album['album_pic_url'] === 'default' ? "uploads/images/albums/emptyalbum.jpg" : "uploads/images/albums/" . $album["album_pic_url"] ?>
                    <img src="<?= $url ?>" alt="Album Cover" style="border-radius: 20px;" />
                    <h2><?= htmlspecialchars($album['album_name']) ?></h2>
                    <div class="actions">
                        </form>
                        <button type="submit" class="main-play-button" name="play_album" onclick="playAlbum(<?= htmlspecialchars($album['album_id']) ?>)"><i class="bi bi-caret-right-fill"></i></button>
                    </div>
                </div>

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
                                <div class='play-button' onclick="playSongFromCard(this)"
                                    data-song-id="<?= $song['song_id'] ?>"
                                    data-song-name="<?= htmlspecialchars($song['song_name']) ?>"
                                    data-song-image="<?= $songImage ?>"
                                    data-song-url="uploads/songs/<?= $song['song_url'] ?>">
                                    <i class='bi bi-caret-right-fill'></i>
                                </div>
                                <input type='checkbox' id='dropdown<?= $song['song_id'] ?>' />
                                <label for='dropdown<?= $song['song_id'] ?>'><i class='bi bi-three-dots-vertical'></i></label>
                                <div class='song-card-dropdown'>
                                    <div class='dropdown-item sub-dropdown hov'>
                                        Add to Playlist
                                        <div class='sub-dropdown-content'>
                                            <?php foreach ($playlists as $playlist): ?>
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
        <?php include "song.php" ?>
    </main>
    <?php include "includes/searchbar.php" ?>
    <script src="js/song.js"></script>
</body>

</html>