<?php
include "includes/sessions/false.php";
include "includes/config.php";
$username = $_SESSION["username"];

$userQuery = $pdo->prepare("SELECT user_id FROM users WHERE username = :username");
$userQuery->execute(["username" => $username]);
$albumUser = $userQuery->fetch();
?>

<html>

<head>
  <title>Albums | Beatwaves</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css"
    rel="stylesheet" />
  <link rel="stylesheet" href="css/main.css" />
  <link rel="stylesheet" href="css/sidebar.css" />
  <link rel="stylesheet" href="css/cards.css" />
</head>

<body>
  <?php include "includes/sidebar.php" ?>
  <main>
    <h1 class="breadcrumb">Albums</h1>
    <section>
      <div class="square-cards">
        <?php
        try {
          $albumsQuery = $pdo->prepare("SELECT * FROM albums");
          $albumsQuery->execute();

          foreach ($albumsQuery as $album) {
            if ($albumUser["user_id"] !== $album["user_id"]) {
              $image = $album["album_pic_url"] !== 'default'
                ? 'uploads/images/albums/' . $album['album_pic_url']
                : 'uploads/images/albums/emptyalbum.jpg';

              echo "
              <a href='album.php?album=" . $album['album_id'] . "' class='card'>
              <img src='" . $image . "' alt='album' />
              <span>{$album['album_name']}</span>
              </a>
              ";
            }
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