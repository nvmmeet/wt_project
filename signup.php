<?php
include "includes/config.php";
include "includes/sessions/true.php";

$fieldError = "";
$usernameError = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $fieldError = "";
  $usernameError = "";
  $name = $_POST['nameof'];
  $dob = $_POST['dob'];
  $uname = $_POST['username'];
  $pass = $_POST['password'];

  if (empty($name) || empty($dob) || empty($uname) || empty($pass)) {
    $fieldError = "Fields should not be empty!";
  }

  if (empty($fieldError)) {

    $query = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $query->execute(['username' => $uname]);
    $user = $query->fetch();

    if ($user) {
      $usernameError = "Username Already Taken";
    }
  }

  if (empty($fieldError) && empty($usernameError)) {
    $hashPass = password_hash($pass, PASSWORD_BCRYPT);
    $insertQuery = $pdo->prepare("INSERT INTO users(name,dob,username,profile_pic_url,password) VALUES(:name,:dob,:username,'default',:password)");
    $insertQuery->execute([
      'name' => $name,
      'dob' => $dob,
      'username' => $uname,
      'password' => $hashPass,
    ]);

    $_SESSION['username'] = $uname;
    header("Location: index.php");
    exit();
  }
}

?>

<!DOCTYPE html>
<html>

<head>
  <title>Sign Up | Beatwaves</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/main.css" />
  <link rel="stylesheet" href="css/forms.css" />
</head>

<body>
  <div class="form-container center">
    <form action="signup.php" method="post" onsubmit="return validateSignUp(event);" name="signup">
      <h1 class="form-heading">Sign Up</h1>
      <div class="form-field">
        <input type="text" placeholder="Name..." name="nameof" />
        <i class="bi bi-person"></i>
      </div>
      <div class="error-field name"></div>
      <div class="form-field">
        <input type="date" name="dob" />
        <i class="bi bi-calendar"></i>
      </div>
      <div class="form-field">
        <input type="text" placeholder="Username..." name="username" />
        <i class="bi bi-lock"></i>
      </div>
      <div class="error-field username"> <?= $usernameError ?? '' ?></div>
      <div class="form-field">
        <i class="bi bi-eye show-pass" onclick="showPass()"></i>
        <input type="password" placeholder="Password..." name="password" id="pass" />
        <i class="bi bi-shield-check"></i>
      </div>
      <div class="error-field password"><?= $fieldError ?? '' ?></div>
      <button type="submit">Submit</button>
      <div class="form-link">
        Already Have an Account? <a href="login.php">Login</a>
      </div>
    </form>
  </div>
  <script src="js/forms.js"></script>
</body>

</html>