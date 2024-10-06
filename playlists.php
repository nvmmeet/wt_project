<?php
include "includes/config.php";
include "includes/sessions/false.php";

$username = $_SESSION["username"];

// Get the user ID of the currently logged-in user
$userQuery = $pdo->prepare("SELECT user_id FROM users WHERE username = :username");
$userQuery->execute(['username' => $username]);
$user = $userQuery->fetch();
$user_id = $user['user_id'];

// Fetch playlists created by the user
$playlistsQuery = $pdo->prepare("SELECT playlist_id, playlist_name, playlist_pic_url FROM playlists WHERE user_id = :user_id");
$playlistsQuery->execute(['user_id' => $user_id]);
$playlists = $playlistsQuery->fetchAll();

$database->close();
?>

<html>

<head>
  <title>Playlists | Beatwaves</title>
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
    <h1 class="breadcrumb">Your Playlists</h1>
    <section>
      <div class="rectangle-cards">
        <?php
        try {
          if (count($playlists)) {

            foreach ($playlists as $playlist) {
              $image = $playlist["playlist_pic_url"] === 'default'
                ? 'uploads/images/playlist/' . $album['playlist_pic_url']
                : 'uploads/images/playlist/emptyplaylist.jpg';

              echo "
              <a href='playlist.php?playlist=" . $playlist['playlist_id'] . "' class='card'>
              <img src='" . $image . "' alt='playlist' />
              <span>{$playlist['playlist_name']}</span>
              </a>
              ";
            }
          } else {
            include "includes/nothing.php";
          }
        } catch (PDOException $e) {
          echo "Error: " . $e->getMessage();
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