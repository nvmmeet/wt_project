<?php
include "includes/sessions/false.php";
include "includes/config.php";
$username = $_SESSION["username"];
?>
<html>

<head>
  <title>Artists| Beatwaves</title>
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
    <h1 class="breadcrumb">Artists</h1>
    <section>
      <div class="square-cards circle">

        <?php
        try {
          $artistQuery = $pdo->prepare("SELECT user_id,username,name,profile_pic_url FROM users");
          $artistQuery->execute();

          foreach ($artistQuery as $artist) {
            if ($username !== $artist["username"]) {
              $image = $artist["profile_pic_url"] !== 'default'
                ? 'uploads/images/users/' . $artist['profile_pic_url']
                : 'uploads/images/users/emptyuser.jpg';

              echo "
                <a href='artist.php?artist=" . $artist['user_id'] . "' class='card'>
              <img src='" . $image . "' alt='artist' />
              <span>{$artist['name']}</span>
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