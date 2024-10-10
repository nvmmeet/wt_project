<?php
include "includes/sessions/false.php";
include "includes/config.php";

$username = $_SESSION["username"];

// Get the user ID of the currently logged-in user
$userQuery = $pdo->prepare("SELECT user_id FROM users WHERE username = :username");
$userQuery->execute(['username' => $username]);
$user = $userQuery->fetch();
$user_id = $user['user_id'];

// Fetch the artists followed by the user
$followedArtistsQuery = $pdo->prepare("
  SELECT DISTINCT u.user_id, u.name, u.profile_pic_url 
  FROM social s
  JOIN users u ON s.followed_id = u.user_id
  WHERE s.following_id = :user_id
");
$followedArtistsQuery->execute(['user_id' => $user_id]);
$followedArtists = $followedArtistsQuery->fetchAll();

$database->close();
?>

<html>

<head>
  <title>Your Followed Artists | Beatwaves</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/sidebar.css" />
  <link rel="stylesheet" href="css/main.css" />
  <link rel="stylesheet" href="css/cards.css" />
</head>

<body>
  <?php include "includes/sidebar.php" ?>

  <main>
    <h1 class="breadcrumb">Your Followed Artists</h1>
    <section>
      <div class="square-cards circle">
        <?php if (count($followedArtists) > 0): ?>
          <?php foreach ($followedArtists as $artist): ?>
            <a href="artist.php?artist=<?= $artist['user_id'] ?>" class="card">
              <?php $artistUrl = $artist['profile_pic_url'] === "default" ? "uploads/images/users/emptyuser.jpg" : "uploads/images/users/" . $artist["profile_pic_url"] ?>
              <img src="<?= $artistUrl ?>" alt="<?= htmlspecialchars($artist['name']) ?>" />
              <span><?= htmlspecialchars($artist['name']) ?></span>
            </a>
          <?php endforeach; ?>
        <?php else: include "includes/nothing.php";  ?>
        <?php endif; ?>
      </div>
    </section>
  </main>

  <?php include "includes/searchbar.php" ?>
  <script src="js/events.js"></script>
  <script src="js/load.js"></script>
</body>

</html>