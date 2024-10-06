<?php
include "includes/config.php";
include "includes/sessions/false.php";

$username = $_SESSION["username"];

$query = $pdo->prepare("SELECT user_id, name, profile_pic_url, dob FROM users WHERE username = :username");
$query->execute(["username" => $username]);
$user = $query->fetch();
$user_id = $user['user_id'];
$database->close();

if ($user["dob"] === '0000-00-00' || $user["dob"] === null) {
  $formattedDob = "Date not set";
} else {
  $formattedDob = date('d-m-Y', strtotime($user["dob"]));
}

$profile_pic_url = $user["profile_pic_url"] === "default" ? "uploads/images/users/emptyuser.jpg" : "uploads/images/users/" . htmlspecialchars($user["profile_pic_url"]);

$songQuery = $pdo->prepare("SELECT COUNT(*) AS song_count FROM songs WHERE user_id = :user_id");
$songQuery->execute(['user_id' => $user_id]);
$songCount = $songQuery->fetch()['song_count'];

$albumQuery = $pdo->prepare("SELECT COUNT(*) AS album_count FROM albums WHERE user_id = :user_id");
$albumQuery->execute(['user_id' => $user_id]);
$albumCount = $albumQuery->fetch()['album_count'];

$playlistQuery = $pdo->prepare("SELECT COUNT(*) AS playlist_count FROM playlists WHERE user_id = :user_id");
$playlistQuery->execute(['user_id' => $user_id]);
$playlistCount = $playlistQuery->fetch()['playlist_count'];

$followersQuery = $pdo->prepare("SELECT COUNT(*) AS follower_count FROM social WHERE followed_id = :user_id");
$followersQuery->execute(['user_id' => $user_id]);
$followerCount = $followersQuery->fetch()['follower_count'];

$followingQuery = $pdo->prepare("SELECT COUNT(*) AS following_count FROM social WHERE following_id = :user_id");
$followingQuery->execute(['user_id' => $user_id]);
$followingCount = $followingQuery->fetch()['following_count'];

$favSongQuery = $pdo->prepare("SELECT COUNT(*) AS fav_song_count FROM fav_songs WHERE user_id = :user_id");
$favSongQuery->execute(['user_id' => $user_id]);
$favSongCount = $favSongQuery->fetch()['fav_song_count'];

$favArtistQuery = $pdo->prepare("SELECT COUNT(DISTINCT s.user_id) AS fav_artist_count 
                                FROM fav_songs fs
                                JOIN songs s ON fs.song_id = s.song_id
                                WHERE fs.user_id = :user_id");
$favArtistQuery->execute(['user_id' => $user_id]);
$favArtistCount = $favArtistQuery->fetch()['fav_artist_count'];

?>
<html>

<head>
  <title><?= $user["name"] ?> | Beatwaves</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/sidebar.css" />
  <link rel="stylesheet" href="css/main.css" />
  <link rel="stylesheet" href="css/profile.css" />
  <link rel="stylesheet" href="css/modals.css" />
</head>

<body>
  <?php include "includes/sidebar.php"; ?>
  <main class="w75">
    <h1 class="breadcrumb">
      Your Profile
      <input type="checkbox" id="breadcrumb">
      <label for="breadcrumb" class="breadcrumb-actions">
        <i class="bi bi-pencil"></i>
      </label>
      <div class="breadcrumb-dropdown">
        <div class="dropdown-item" onclick="toggleModal()">
          <span>Profile Picture</span>
        </div>
        <div class="dropdown-item">
          <a href="editinfo.php">Information</a>
        </div>
        <div class="dropdown-item">
          <a href="changepassword.php">Change Password</a>
        </div>
        <div class="dropdown-item del">
          <a href="deleteuser.php">Delete Account</a>
        </div>
      </div>
    </h1>
    <section>
      <div class="profile-main">
        <img src="<?= $profile_pic_url ?>" alt="profile-pic" draggable="false" />
        <div class="profile-details">
          <span><?= htmlspecialchars($username) ?></span>
          <span><?= htmlspecialchars($user["name"]) ?></span>
          <span>Date of Birth: <?= $formattedDob ?></span>

          <div class="profile-follow">
            <div>Followers : <?= $followerCount ?></div>
            <div>Following : <?= $followingCount ?></div>
          </div>
        </div>
      </div>

      <div class="profile-main">
        <div class="profile-upload">
          <div class="row">
            Uploads :
            <span>Songs : <?= $songCount ?> |</span>
            <span>Albums : <?= $albumCount ?></span>
          </div>
        </div>
      </div>

      <div class="profile-main">
        <div class="profile-upload">
          <div class="row">
            Library :
            <span>Favorite Songs : <?= $favSongCount ?> |</span>
            <span>Favorite Artists : <?= $favArtistCount ?> |</span>
            <span>Playlists : <?= $playlistCount ?></span>
          </div>
        </div>
      </div>
    </section>
  </main>

  <div class="modal-bg" id="modal-bg">
    <div class="modal pfp-upload">
      <i class="bi bi-x-lg close-modal" onclick="toggleModal()"></i>
      <form action="pfpupload.php" method="POST" enctype="multipart/form-data">
        <img src='<?= $profile_pic_url ?>' alt="upload-pic" id="image_preview">
        <input type="file" name="image_upload" accept="image/*" id="image_upload" onchange="changeImage(event)">
        <label for="image_upload">Change</label>
        <button type="submit" class="upload_btn">Upload</button>
      </form>
    </div>

  </div>
  <script src="js/events.js"></script>
  <script>
    function toggleModal() {
      let modalBg = document.querySelector(".modal-bg");
      modalBg.classList.toggle("active")
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
  </script>
  <script>

  </script>
</body>

</html>