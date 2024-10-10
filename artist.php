<?php
include "includes/sessions/false.php";
include "includes/config.php";

$loggedInUsername = $_SESSION['username'];
$user_id = intval($_GET['artist']);
$error = ''; // Initialize error variable

if (!$user_id) {
    $error = "Bad Request!";
} else {
    // Query to fetch logged-in user's ID based on username
    $loggedInUserQuery = $pdo->prepare("SELECT user_id FROM users WHERE username = :username");
    $loggedInUserQuery->execute(['username' => $loggedInUsername]);
    $loggedInUser = $loggedInUserQuery->fetch(PDO::FETCH_ASSOC);

    // Check if logged-in user exists
    if (!$loggedInUser) {
        $error = "User Not Found!";
    } else {
        $loggedInUserId = $loggedInUser['user_id']; // Get logged-in user's ID

        $isFollowed = $pdo->prepare("SELECT * FROM social WHERE followed_id = :artist_id AND following_id = :user_id");
        $isFollowed->execute(["artist_id" => $user_id, "user_id" => $loggedInUserId]);
        $iffollow = $isFollowed->fetch();

        // Query to fetch artist details
        $artistPageQuery = $pdo->prepare("
            SELECT 
                u.user_id,
                u.username,
                u.name,
                u.profile_pic_url,
                a.album_id,
                a.album_name,
                a.album_pic_url,
                s.song_id,
                s.song_name,
                s.song_pic_url
            FROM 
                users u
            LEFT JOIN 
                albums a ON u.user_id = a.user_id
            LEFT JOIN 
                album_songs als ON a.album_id = als.album_id
            LEFT JOIN 
                songs s ON als.song_id = s.song_id
            WHERE 
                u.user_id = :user_id
        ");

        $artistPageQuery->execute(["user_id" => $user_id]);
        $results = $artistPageQuery->fetchAll(PDO::FETCH_ASSOC);

        // Check if artist exists
        if (empty($results)) {
            $error = "User Not Found!";
        } else {
            $artist = reset($results);

            // Redirect if user_id matches the logged-in user ID
            if ($artist['user_id'] === $loggedInUserId) {
                header("Location: profile.php");
                exit(); // Ensure no further code is executed after the redirect
            }

            // Count followers and following
            $followerQuery = $pdo->prepare("SELECT COUNT(*) as count FROM social WHERE followed_id = :user_id");
            $followerQuery->execute(['user_id' => $user_id]);
            $followerCount = $followerQuery->fetchColumn();

            $followingQuery = $pdo->prepare("SELECT COUNT(*) as count FROM social WHERE following_id = :user_id");
            $followingQuery->execute(['user_id' => $user_id]);
            $followingCount = $followingQuery->fetchColumn();

            // Fetch the liked songs for the logged-in user
            $favoritesQuery = $pdo->prepare("
                SELECT song_id FROM fav_songs 
                WHERE user_id = (SELECT user_id FROM users WHERE username = :username)
            ");
            $favoritesQuery->execute(['username' => $loggedInUsername]);
            $likedSongs = $favoritesQuery->fetchAll(PDO::FETCH_COLUMN, 0);

            // Fetch user's playlists
            $playlistsQuery = $pdo->prepare("
                SELECT playlist_id, playlist_name 
                FROM playlists 
                WHERE user_id = :loggedInUserId
            ");
            $playlistsQuery->execute(['loggedInUserId' => $loggedInUserId]);
            $playlists = $playlistsQuery->fetchAll(PDO::FETCH_ASSOC);

            // Fetch all songs by the artist
            $allSongsQuery = $pdo->prepare("
                SELECT s.song_id, s.song_name, s.song_pic_url 
                FROM songs s 
                WHERE s.user_id = :user_id
            ");
            $allSongsQuery->execute(['user_id' => $user_id]);
            $allSongs = $allSongsQuery->fetchAll(PDO::FETCH_ASSOC);
        }
    }
}
?>

<html>

<head>
    <title><?= htmlspecialchars($artist['username'] ?? 'Artist Profile') ?> | Beatwaves</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/sidebar.css" />
    <link rel="stylesheet" href="css/main.css" />
    <link rel="stylesheet" href="css/profile.css" />
    <link rel="stylesheet" href="css/cards.css" />
</head>

<body>
    <?php include "includes/sidebar.php"; ?>
    <main>
        <h1 class="breadcrumb"><?= htmlspecialchars($error) ?: 'Artist Profile' ?></h1> <!-- Show error or default heading -->
        <?php if (!$error): ?>
            <section>
                <div class="profile-main">
                    <img src="<?= $artist['profile_pic_url'] === 'default' ? 'uploads/images/users/emptyuser.jpg' : 'uploads/images/users/' . $artist["profile_pic_url"] ?>" alt="profile-pic" draggable="false" />
                    <div class="profile-details">
                        <span><?= htmlspecialchars($artist['username']) ?></span>
                        <span><?= htmlspecialchars($artist['name']) ?></span>

                        <div class="profile-follow">
                            <div>Followers: <?= htmlspecialchars($followerCount) ?></div>
                            <div>Following: <?= htmlspecialchars($followingCount) ?></div>
                        </div>
                        <?php if (empty($iffollow)): ?>
                            <a href="follow.php?id=<?= htmlspecialchars($artist['user_id']) ?>">
                                <button class="follow-btn">Follow</button>
                            </a>
                        <?php else: ?>
                            <a href="unfollow.php?id=<?= htmlspecialchars($artist['user_id']) ?>">
                                <button class="follow-btn">Unfollow</button>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

            <section>
                <div class="section-upper">
                    <h2>Albums</h2>
                </div>
                <div class="rectangle-cards">
                    <?php
                    $albums = [];
                    foreach ($results as $row) {
                        if (!isset($albums[$row['album_id']])) {
                            $albums[$row['album_id']] = [
                                'album_name' => $row['album_name'],
                                'album_pic_url' => $row['album_pic_url'] ?: 'emptyalbum.jpg',
                                'songs' => []
                            ];
                        }
                        if ($row['song_id']) {
                            $albums[$row['album_id']]['songs'][] = [
                                'song_id' => $row['song_id'],
                                'song_name' => $row['song_name'],
                                'song_pic_url' => $row['song_pic_url']
                            ];
                        }
                    }
                    foreach ($albums as $album_id => $album) {
                        $image = $album['album_pic_url'] !== 'default'
                            ? 'uploads/images/albums/' . htmlspecialchars($album['album_pic_url'])
                            : 'uploads/images/albums/emptyalbum.jpg';

                        echo "
                        <a href='album.php?album=" . htmlspecialchars($album_id) . "' class='card'>
                            <img src='" . htmlspecialchars($image) . "' alt='album' />
                            <span>" . htmlspecialchars($album['album_name']) . "</span>
                        </a>";
                    }
                    ?>
                </div>
            </section>

            <section>
                <div class="section-upper">
                    <h2>Songs</h2>
                </div>
                <div class="song-cards">
                    <?php  // Fetch and display songs not in albums
                    foreach ($allSongs as $song) {
                        $isLiked = in_array($song['song_id'], $likedSongs);
                        $songImage = $song['song_pic_url'] !== 'default' ? 'uploads/images/songs/' . htmlspecialchars($song['song_pic_url']) : 'uploads/images/songs/emptysong.jpg';

                        echo "
                        <div class='song-card'>
                            <img src='" . htmlspecialchars($songImage) . "' alt='song' />
                            <span>" . htmlspecialchars($song['song_name']) . "</span>
                            <p>" . htmlspecialchars($artist['username']) . "</p>
                            <div class='play-button'>
                                <i class='bi bi-caret-right-fill'></i>
                            </div>
                            <input type='checkbox' id='dropdown" . htmlspecialchars($song['song_id']) . "'/>
                            <label for='dropdown" . htmlspecialchars($song['song_id']) . "'><i class='bi bi-three-dots-vertical'></i></label>
                            <div class='song-card-dropdown'>
                                <div class='dropdown-item sub-dropdown hov'>
                                    Add to Playlist
                                    <div class='sub-dropdown-content'>";

                        if (count($playlists) > 0) {
                            foreach ($playlists as $playlist) {
                                $inPlaylistQuery = $pdo->prepare("SELECT 1 FROM playlist_songs WHERE playlist_id = :playlist_id AND song_id = :song_id");
                                $inPlaylistQuery->execute(['playlist_id' => $playlist['playlist_id'], 'song_id' => $song['song_id']]);
                                $alreadyAdded = $inPlaylistQuery->fetchColumn();

                                echo "<a href='addToPlaylist.php?song_id=" . htmlspecialchars($song['song_id']) . "&playlist_id=" . htmlspecialchars($playlist['playlist_id']) . "' class='add-to-playlist-btn'>
                                    " . ($alreadyAdded ? 'Already in playlist' : 'Add to ' . htmlspecialchars($playlist['playlist_name'])) . "
                                </a>";
                            }
                        } else {
                            echo "No Playlists Available";
                        }

                        echo "
                                    </div>
                                </div>
                                <div class='dropdown-item'>
                                    " . ($isLiked ? "<a href='removefromfav.php?song=" . htmlspecialchars($song['song_id']) . "'>Unfavourite it!</a>" : "<a href='addtofav.php?song=" . htmlspecialchars($song['song_id']) . "'>Favourite it!</a>") . "
                                </div>
                            </div>
                        </div>";
                    }
                    ?>
                </div>
            </section>
        <?php endif; ?>
    </main>
    <?php include "includes/searchbar.php" ?>
</body>

</html>