<?php
include "includes/sessions/false.php";
include "includes/config.php";

$username = $_SESSION["username"];

// Fetch user information
$userQuery = $pdo->prepare("SELECT user_id FROM users WHERE username = :username");
$userQuery->execute(['username' => $username]);
$user = $userQuery->fetch();
$user_id = $user['user_id'];

// Fetch albums excluding those created by the logged-in user
$albumsQuery = $pdo->prepare("SELECT album_id, album_name, album_pic_url FROM albums WHERE user_id != :user_id LIMIT 4");
$albumsQuery->execute(['user_id' => $user_id]);
$albums = $albumsQuery->fetchAll();

// Fetch artists excluding the logged-in user
$artistsQuery = $pdo->prepare("SELECT user_id, name, profile_pic_url FROM users WHERE user_id != :user_id LIMIT 5");
$artistsQuery->execute(['user_id' => $user_id]);
$artists = $artistsQuery->fetchAll();

// Fetch random songs excluding those uploaded by the logged-in user
$randomSongsQuery = $pdo->prepare("SELECT song_id, song_name, song_pic_url, user_id FROM songs WHERE user_id != :user_id ORDER BY RAND() LIMIT 5");
$randomSongsQuery->execute(['user_id' => $user_id]);
$randomSongs = $randomSongsQuery->fetchAll();

// Fetch playlists for the logged-in user
$playlistsQuery = $pdo->prepare("SELECT playlist_id, playlist_name FROM playlists WHERE user_id = :user_id");
$playlistsQuery->execute(['user_id' => $user_id]);
$playlists = $playlistsQuery->fetchAll();
?>

<html>

<head>
  <title>Home | Beatwaves</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/main.css" />
  <link rel="stylesheet" href="css/cards.css" />
  <link rel="stylesheet" href="css/sidebar.css" />
</head>

<body>
  <?php include "includes/sidebar.php"; ?>
  <main>
    <h1 class="breadcrumb">Home</h1>

    <!-- Albums Section -->
    <section>
      <div class="section-upper">
        <h2>Albums</h2>
        <a href="albums.php">View all <i class="bi bi-caret-right"></i></a>
      </div>
      <div class="rectangle-cards">
        <?php if (count($albums) > 0) {
          foreach ($albums as $album) {
            $albumImage = $album['album_pic_url'] === 'default'
              ? 'uploads/images/albums/emptyalbum.jpg'
              : 'uploads/images/albums/' . $album['album_pic_url'];
            echo "
              <a href='album.php?album=" . $album['album_id'] . "' class='card'>
                <img src='" . $albumImage . "' alt='" . htmlspecialchars($album['album_name']) . "' />
                <span>" . htmlspecialchars($album['album_name']) . "</span>
              </a>";
          }
        } else {
          echo "<p>No albums available.</p>";
        } ?>
      </div>
    </section>

    <!-- Artists Section -->
    <section>
      <div class="section-upper">
        <h2>Artists</h2>
        <a href="artists.php">View all <i class="bi bi-caret-right"></i></a>
      </div>
      <div class="square-cards circle">
        <?php if (count($artists) > 0) {
          foreach ($artists as $artist) {
            $artistImage = $artist['profile_pic_url'] === 'default'
              ? 'uploads/images/users/emptyuser.jpg'
              : 'uploads/images/users/' . $artist['profile_pic_url'];
            echo "
              <a href='artist.php?artist=" . $artist['user_id'] . "' class='card'>
                <img src='" . $artistImage . "' alt='" . htmlspecialchars($artist['name']) . "' />
                <span>" . htmlspecialchars($artist['name']) . "</span>
              </a>";
          }
        } else {
          echo "<p>No artists available.</p>";
        } ?>
      </div>
    </section>

    <!-- Recently Played Songs Section -->
    <section>
      <div class="section-upper">
        <h2>Recently Played</h2>
      </div>
      <div class="song-cards">
        <?php
        try {
          if (count($randomSongs) > 0) {
            foreach ($randomSongs as $song) {
              $songImage = $song['song_pic_url'] !== 'default'
                ? 'uploads/images/songs/' . $song['song_pic_url']
                : 'uploads/images/songs/emptysong.jpg';

              // Get the artist name
              $artistQuery = $pdo->prepare("SELECT username FROM users WHERE user_id = :user_id");
              $artistQuery->execute(['user_id' => $song['user_id']]);
              $artist = $artistQuery->fetch();

              // Check if the song is in the user's favorites
              $favQuery = $pdo->prepare("SELECT 1 FROM fav_songs WHERE user_id = :user_id AND song_id = :song_id");
              $favQuery->execute(['user_id' => $user_id, 'song_id' => $song['song_id']]);
              $isInFavourites = $favQuery->fetch();

              echo "
                <div class='song-card'>
                  <img src='" . $songImage . "' alt='" . htmlspecialchars($song['song_name']) . "-card' />
                  <span>" . htmlspecialchars($song['song_name']) . "</span>
                  <p>" . htmlspecialchars($artist['username']) . "</p>
                  <div class='play-button'>
                    <i class='bi bi-caret-right-fill'></i>
                  </div>
                  <input type='checkbox' id='dropdown" . $song['song_id'] . "'/>
                  <label for='dropdown" . $song['song_id'] . "'><i class='bi bi-three-dots-vertical'></i></label>
                  <div class='song-card-dropdown'>
                    <!-- Add to Favourites or Remove from Favourites -->
                  

                    <div class='dropdown-item sub-dropdown hov'>
                      Add to Playlist
                      <div class='sub-dropdown-content'>";

              if (count($playlists) > 0) {
                foreach ($playlists as $playlist) {
                  $inPlaylistQuery = $pdo->prepare("SELECT 1 FROM playlist_songs WHERE playlist_id = :playlist_id AND song_id = :song_id");
                  $inPlaylistQuery->execute(['playlist_id' => $playlist['playlist_id'], 'song_id' => $song['song_id']]);
                  $isInPlaylist = $inPlaylistQuery->fetch();

                  if (!$isInPlaylist) {
                    echo "
                        <div class='dropdown-item'>
                          <a href='addtopl.php?song=" . $song['song_id'] . "&playlist=" . $playlist['playlist_id'] . "'>
                            " . htmlspecialchars($playlist['playlist_name']) . "
                          </a>
                        </div>";
                  }
                }
              }

              echo "
                      </div>
                    </div>
                      <div class='dropdown-item'>
                      " . ($isInFavourites
                ? "<a href='removefromfav.php?song=" . $song['song_id'] . "'>Unfavourite it!</a>"
                : "<a href='addtofav.php?song=" . $song['song_id'] . "'>Favourite it!</a>") . "
                    </div>
                  </div>
                </div>";
            }
          } else {
            echo "<p>No songs available.</p>";
          }
        } catch (PDOException $e) {
          echo "Error: " . $e->getMessage();
        }
        ?>
      </div>
    </section>
  </main>
  <?php include "includes/searchbar.php"; ?>
  <script src="js/events.js"></script>
</body>

</html>