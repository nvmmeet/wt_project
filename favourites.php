<?php
include "includes/sessions/false.php";
include "includes/config.php";
$username = $_SESSION["username"];
?>
<html>

<head>
  <title>Favourites | Beatwaves</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css"
    rel="stylesheet" />
  <link rel="stylesheet" href="css/sidebar.css" />
  <link rel="stylesheet" href="css/main.css" />
  <link rel="stylesheet" href="css/cards.css" />
</head>

<body>
  <?php include "includes/sidebar.php" ?>

  <main>
    <h1 class="breadcrumb">Favourite Songs</h1>
    <section>
      <div class="song-cards">
        <?php
        try {
          // Fetch user_id for the logged-in user
          $userQuery = $pdo->prepare("SELECT user_id FROM users WHERE username = :username");
          $userQuery->execute(['username' => $username]);
          $user = $userQuery->fetch();

          if ($user) {
            $user_id = $user['user_id'];

            // Fetch user's favourite songs
            $favSongQuery = $pdo->prepare("
              SELECT s.song_id, s.song_name, s.song_pic_url, u.name AS artist_name
              FROM fav_songs fs
              JOIN songs s ON fs.song_id = s.song_id
              JOIN users u ON s.user_id = u.user_id
              WHERE fs.user_id = :user_id
            ");
            $favSongQuery->execute(['user_id' => $user_id]);
            $songs = $favSongQuery->fetchAll();

            // Fetch user's playlists
            $playlistQuery = $pdo->prepare("SELECT playlist_id, playlist_name FROM playlists WHERE user_id = :user_id");
            $playlistQuery->execute(['user_id' => $user_id]);
            $playlists = $playlistQuery->fetchAll();

            if (count($songs) > 0) {
              foreach ($songs as $song) {
                // Check if song is already in favorites (always true here because it's the favorites page)
                $isInFavourites = true;

                // Determine image URL
                $image = $song['song_pic_url'] !== 'default'
                  ? 'uploads/images/songs/' . htmlspecialchars($song['song_pic_url'])
                  : 'uploads/images/songs/emptysong.jpg';

                // Render song card
                echo "
                <div class='song-card'>
                  <img src='" . $image . "' alt='song' />
                  <span>" . htmlspecialchars($song['song_name']) . "</span>
                  <p>" . htmlspecialchars($song['artist_name']) . "</p>
                  <div class='play-button'>
                    <i class='bi bi-caret-right-fill'></i>
                  </div>
                  <input type='checkbox' id='dropdown" . $song['song_id'] . "'/>
                  <label for='dropdown" . $song['song_id'] . "'><i class='bi bi-three-dots-vertical'></i></label>
                  <div class='song-card-dropdown'>

                    <div class='dropdown-item sub-dropdown hov'>
                      Add to Playlist
                      <div class='sub-dropdown-content'>";

                // Render playlist options
                if (count($playlists) > 0) {
                  foreach ($playlists as $playlist) {
                    // Check if song is already in the playlist
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
                  <a href='removefromfav.php?song=" . $song['song_id'] . "'>Unfavourite it!</a>
                    </div>
                  </div>
                </div>";
              }
            } else {
              include "includes/nothing.php";
            }
          } else {
            echo "<p>User not found.</p>";
          }
        } catch (PDOException $e) {
          echo "Error: " . htmlspecialchars($e->getMessage());
        }
        ?>
      </div>
    </section>
  </main>

  <?php include "includes/searchbar.php" ?>
  <script src="js/events.js"></script>
  <script src="js/load.js"></script>
</body>

</html>