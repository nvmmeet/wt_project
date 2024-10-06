<?php
include "includes/config.php";
include "includes/sessions/false.php";
$username = $_SESSION["username"];

$error = "";
$error2 = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $oldpass = $_POST["oldpassword"];
    $newpass = $_POST["newpassword"];

    if (empty($oldpass) || empty($newpass)) {
        $error2 = "Fields should not Be empty!";
    }
    if (empty($error2)) {
        $query = $pdo->prepare("SELECT password FROM users where username = :username");
        $query->execute(["username" => $username]);
        $user = $query->fetch();
        if (!password_verify($oldpass, $user['password'])) {
            $error = "Old Credentials does not match!";
        } else {
            $hashPass = password_hash($newpass, PASSWORD_BCRYPT);
            $query2 = $pdo->prepare("UPDATE users SET password = :password WHERE username = :username ");
            $query2->execute(["password" => $hashPass, "username" => $username]);
            header("Location: profile.php");
            exit();
        }
    }
}
?>

<html>

<head>
    <title>Change Password | Beatwaves</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css"
        rel="stylesheet" />
    <link rel="stylesheet" href="css/main.css" />
    <link rel="stylesheet" href="css/forms.css" />
</head>

<body>
    <div class="form-container center">
        <form action="changepassword.php" method="post" onsubmit="return validateChangePassword(event);" name="changepass">
            <div class="form-field">
                <i class="bi bi-eye show-pass" onclick="showPass()"></i>
                <input type="password" placeholder="Old Password..." name="oldpassword" id="pass" />
                <i class="bi bi-lock"></i>
            </div>
            <div class="error-field"><?= $error ?></div>
            <div class="form-field">
                <i class="bi bi-eye show-pass" onclick="showPass2()"></i>
                <input type="password" placeholder="New Password..." name="newpassword" id="pass2" />
                <i class="bi bi-lock"></i>
            </div>
            <div class="error-field password"><?= $error2 ?? "" ?></div>
            <button type="submit">Edit</button>
        </form>
    </div>
    <script src="js/forms.js"></script>
</body>

</html>