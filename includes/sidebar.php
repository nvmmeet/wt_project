<?php
$username = $_SESSION["username"];
$query = $pdo->prepare("SELECT name,profile_pic_url FROM users WHERE username = :username");
$query->execute(["username" => $username]);
$user = $query->fetch();
$profile_pic_url = $user["profile_pic_url"];
if ($profile_pic_url === "default") {
  $profile_pic_url = "uploads/images/users/emptyuser.jpg";
} else {
  $profile_pic_url = "uploads/images/users/" . htmlspecialchars($profile_pic_url);
}
?>

<aside class="sidebar nav">
  <div class="sidebar-top">
    <a href="index.php">
      <img src="images/logo.jpg" alt="logo" />
      <h2>Beatwaves</h2>
    </a>
    <i class="bi bi-list center" onclick="toggleNav()"></i>
  </div>
  <div class="links">
    <div class="link-group">
      <h3>
        <i class="bi bi-compass"></i>
        <span>Explore</span>
      </h3>
      <a href="home.html" class="active">
        <span>Home</span>
      </a>
      <a href="albums.html">
        <span>Albums</span>
      </a>
      <a href="artists.html">
        <span>Artists</span>
      </a>
    </div>
    <div class="link-group">
      <h3>
        <i class="bi bi-collection-play"></i>
        <span>Library</span>
      </h3>
      <a href="favourites.html">
        <span>Favourites</span>
      </a>
      <a href="playlists.html">
        <span>Playlists</span>
      </a>
      <a href="fav-artists.html">
        <span>Followed Artists</span>
      </a>
    </div>
    <div class="link-group">
      <h3>
        <i class="bi bi-person"></i>
        <span>Profile</span>
      </h3>
      <a href="profile.php" class="no-pad">
        <img src="<?= $profile_pic_url ?>" alt="pfp" draggable="false" />
        <span><?= htmlspecialchars($user["name"]) ?></span>
      </a>
      <a href="uploads.html" class="no-pad">
        <i class="bi bi-box-arrow-up"></i>
        <span>My uploads</span>
      </a>
      <a class="no-pad" href='includes/sessions/logout.php'>
        <i class="bi bi-box-arrow-left"></i>
        <span>Logout</span>
      </a>
    </div>
  </div>
</aside>