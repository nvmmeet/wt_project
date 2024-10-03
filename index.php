<?php
include "includes/sessions/false.php";
include "includes/config.php";
$database = new Database();
$pdo = $database->open();
?>

<html>

<head>
  <title>Home | Beatwaves</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/main.css" />
  <link rel="stylesheet" href="css/sidebar.css" />
</head>

<body>
  <?php include "includes/sidebar.php"; ?>
  <main>
    <h1 class="breadcrumb">Home</h1>
    <section>
      <div class="section-upper">
        <h2>Albums</h2>
        <a href="albums.html">
          View all
          <i class="bi bi-caret-right"></i>
        </a>
      </div>
      <div class="rectangle-cards">
        <a href="album.html" class="card">
          <img src="images/logo.jpg" alt="album" />
          <span>Beatles</span>
        </a>
        <a href="album.html" class="card">
          <img src="images/logo.jpg" alt="album" />
          <span>Lambeled</span>
        </a>
        <a href="album.html" class="card">
          <img src="images/logo.jpg" alt="album" />
          <span>Let it go</span>
        </a>
        <a href="album.html" class="card">
          <img src="images/logo.jpg" alt="album" />
          <span>Let it go</span>
        </a>
      </div>
    </section>
    <section>
      <div class="section-upper">
        <h2>Artists</h2>
        <a href="artists.html">
          View all
          <i class="bi bi-caret-right"></i>
        </a>
      </div>
      <div class="square-cards">
        <a href="artist.html" class="card">
          <img src="images/logo.jpg" alt="artist" />
          <span>Beatles</span>
        </a>
        <a href="artist.html" class="card">
          <img src="images/logo.jpg" alt="artist" />
          <span>Lambeled</span>
        </a>
        <a href="artist.html" class="card">
          <img src="images/logo.jpg" alt="artist" />
          <span>Let it go</span>
        </a>
        <a href="artist.html" class="card">
          <img src="images/logo.jpg" alt="artist" />
          <span>Let it go</span>
        </a>
        <a href="artist.html" class="card">
          <img src="images/logo.jpg" alt="artist" />
          <span>Let it go</span>
        </a>
      </div>
    </section>
    <section>
      <div class="section-upper">
        <h2>Recently Played</h2>
      </div>
      <div class="song-cards">
        <div class="song-card">
          <img src="images/bg.jpg" alt="song-card" />
          <span>Beatles</span>
          <p>Travis Scott</p>
          <div class="play-button">
            <i class="bi bi-caret-right-fill"></i>
          </div>
        </div>
        <div class="song-card">
          <img src="images/bg.jpg" alt="song-card" />
          <span>Beatles</span>
          <p>Travis Scott</p>
          <div class="play-button">
            <i class="bi bi-caret-right-fill"></i>
          </div>
        </div>
        <div class="song-card">
          <img src="images/bg.jpg" alt="song-card" />
          <span>Beatles</span>
          <p>Travis Scott</p>
          <div class="play-button">
            <i class="bi bi-caret-right-fill"></i>
          </div>
        </div>
        <div class="song-card">
          <img src="images/bg.jpg" alt="song-card" />
          <span>Beatles</span>
          <p>Travis Scott</p>
          <div class="play-button">
            <i class="bi bi-caret-right-fill"></i>
          </div>
        </div>
        <div class="song-card">
          <img src="images/bg.jpg" alt="song-card" />
          <span>Beatles</span>
          <p>Travis Scott</p>
          <div class="play-button">
            <i class="bi bi-caret-right-fill"></i>
          </div>
        </div>
      </div>
    </section>
  </main>

  <div id="use-search"></div>
  <script src="js/events.js"></script>
  <script src="js/load.js"></script>
</body>

</html>