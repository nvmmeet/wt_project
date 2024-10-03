<?php
include "includes/config.php";
include "includes/sessions/false.php";

$username = $_SESSION["username"];
$query = $pdo->prepare("SELECT name,profile_pic_url,dob FROM users WHERE username = :username");
$query->execute(["username" => $username]);
$user = $query->fetch();
$database->close();

if ($user["dob"] === '0000-00-00' || $user["dob"] === null) {
  $formattedDob = "Date not set";
} else {
  $formattedDob = date('d-m-Y', strtotime($user["dob"]));
}
$profile_pic_url = $user["profile_pic_url"];
if ($profile_pic_url === "default") {
  $profile_pic_url = "uploads/images/users/emptyuser.jpg";
} else {
  $profile_pic_url = "uploads/images/users/" . htmlspecialchars($profile_pic_url);
}
?>
<html>

<head>
  <title>Home | Beatwaves</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/main.css" />
  <link rel="stylesheet" href="css/profile.css" />
  <link rel="stylesheet" href="css/sidebar.css" />
</head>

<body>
  <?php include "includes/sidebar.php"; ?>
  <main class="w75">
    <h1 class="breadcrumb">
      Your Profile
      <a href="editinfo.php">
        <i class="bi bi-pencil"></i>
      </a>
    </h1>
    <section>
      <div class="profile-main">
        <img src="<?= $profile_pic_url ?>" alt="profile-pic" draggable="false" />
        <div class="profile-details">
          <span class="username">
            <?= htmlspecialchars($username) ?></span>
          <span class="name">
            <?= htmlspecialchars($user["name"]) ?></span>
          <span class="dob"><span>Date of Birth : </span><?= $formattedDob ?></span>

          <div class="profile-follow">
            <div>Followers 28 |</div>
            <div>Following 56</div>
          </div>
        </div>
      </div>
      <div class="profile-main">
        <div class="profile-upload">
          <div class="row">
            Uploads :
            <span>Songs 58 |</span>
            <span>Albums 12</span>
          </div>
        </div>
      </div>
      <div class="profile-main">
        <a href="changepassword.php">Change Password</a>
      </div>
    </section>
  </main>

  <script src="js/events.js"></script>
  <script src="js/load.js"></script>
</body>

</html>