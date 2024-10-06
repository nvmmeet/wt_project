<?php
include "includes/sessions/true.php";
include "includes/config.php";


$error = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST["username"];
  $password = $_POST["password"];

  if (empty($username) || empty($password)) {
    $error = "Fields should not Be empty!";
  }

  if (empty($error)) {
    $query = $pdo->prepare("SELECT password FROM users WHERE username = :username");
    $query->execute(["username" => $username]);
    $user = $query->fetch();

    if (!$user) {
      $error = "User Doesn't Exist!";
    } else {
      if (!password_verify($password, $user['password'])) {
        $error = "Credentials doesn't Match!";
      } else {
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit();
      }
    }
  }
}

?>

<html>

<head>
  <title>Login | Beatwaves</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet" />
  <link rel="stylesheet" href="css/main.css" />
  <link rel="stylesheet" href="css/forms.css" />
</head>

<body>
  <div class="form-container center">
    <form action="login.php" method="post" onsubmit="return validateLogin(event);" name="login">
      <h1 class="form-heading">Login</h1>
      <div class="form-field">
        <input type="text" placeholder="Username..." name="username" />
        <i class="bi bi-person"></i>
      </div>
      <div class="form-field">
        <i class="bi bi-eye show-pass" onclick="showPass()"></i>
        <input type="password" placeholder="password..." name="password" id="pass" />
        <i class="bi bi-lock"></i>
      </div>
      <div class="error-field password"><?= $error ?? "" ?></div>
      <button type="submit">Submit</button>
      <div class="form-link">
        Don't Have an Account? <a href="signup.php">Sign Up</a>
      </div>
    </form>
  </div>
  <script src="js/forms.js"></script>
</body>

</html>