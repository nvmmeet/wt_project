<?php
include "includes/config.php";
include "includes/sessions/false.php";

$username = $_SESSION["username"];

$userQuery = $pdo->prepare("SELECT user_id FROM users WHERE username = :username");
$userQuery->execute(['username' => $username]);
$user = $userQuery->fetch();
$user_id = $user['user_id'];

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
  <link rel="stylesheet" href="css/modals.css" />
</head>

<body>
  <?php include "includes/sidebar.php" ?>

  <main>
    <h1 class="breadcrumb">
      Your Profile
      <div class="breadcrumb-actions" onclick="toggleModal()">
        <i class="bi bi-plus" style="font-size:30px;"></i>
      </div>
    </h1>
    <section>
      <div class="rectangle-cards">
        <?php
        try {
          if (count($playlists)) {

            foreach ($playlists as $playlist) {
              $image = $playlist["playlist_pic_url"] === 'default'
                ? 'uploads/images/playlists/emptyplaylist.jpg'
                : 'uploads/images/playlists/' . $playlist['playlist_pic_url'];

              echo "
              <a href='playlist.php?playlist=" . $playlist['playlist_id'] . "' class='card' style='object-fit:cover;'>
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

  <div class="modal-bg">
    <div class="modal upload">
      <i class="bi bi-x-lg close-modal" onclick="toggleModal()"></i>
      <form action="uploadplaylist.php" method="POST" enctype="multipart/form-data" name="playlist_form" onsubmit="return validateForm2()">
        <?php $defaulturl = "uploads/images/playlists/emptyplaylist.jpeg" ?>
        <img src='<?= $defaulturl ?>' alt="upload-pic" id="image_preview" class="rad15">
        <input type="file" name="playlist_image_upload" accept="image/*" id="playlist_image_upload" onchange="changeImage(event)">
        <label for="playlist_image_upload">Change Image</label>
        <input type="text" name="album_name" id="album_name" placeholder="Playlist Name...">
        <button type="submit" class="upload_btn">Upload</button>
      </form>
    </div>
  </div>

  <?php include "includes/searchbar.php" ?>
  <script>
    function toggleModal() {
      let modalBg = document.querySelector(".modal-bg");
      modalBg.classList.toggle("active");
    }

    function changeImage(event) {
      const imagePreview = document.getElementById('image_preview');
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          imagePreview.src = e.target.result;
          imagePreview.style.display = 'block';
        }
        reader.readAsDataURL(file);
      } else {
        imagePreview.style.display = 'none';
      }
    };

    function validateForm() {
      const songUrl = document.getElementById("song_url").value;
      const songName = document.getElementById("song_name").value;
      if (!songUrl) {
        return false;
      }
      if (!songName) {
        return false;
      }
      return true;
    }
  </script>
  <script src="js/events.js"></script>
  <script src="js/load.js"></script>
</body>

</html>