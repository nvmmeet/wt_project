<?php
include "includes/config.php";
include "includes/sessions/false.php";
$username = $_SESSION["username"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $newname = isset($_POST["newname"]) ? trim($_POST["newname"]) : '';
  $newdob = isset($_POST["newdob"]) ? trim($_POST["newdob"]) : '';

  $fieldsToUpdate = [];
  $parameters = ["username" => $username];

  if (!empty($newname)) {
    $fieldsToUpdate[] = "name = :name";
    $parameters["name"] = $newname;
  }

  if (!empty($newdob)) {
    $fieldsToUpdate[] = "dob = :dob";
    $parameters["dob"] = $newdob;
  }

  if (!empty($fieldsToUpdate)) {
    $sql = "UPDATE users SET " . implode(", ", $fieldsToUpdate) . " WHERE username = :username";
    $query = $pdo->prepare($sql);
    $query->execute($parameters);
  }

  header("Location: profile.php");
  exit();
}
?>

<html>

<head>
  <title>Edit Information| Beatwaves</title>
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
    <form action="editinfo.php" method="post">
      <div class="form-field">
        <input type="text" placeholder="Name..." name="newname" />
        <i class="bi bi-person"></i>
      </div>
      <div class="form-field">
        <input type="date" name="newdob" />
        <i class="bi bi-calendar"></i>
      </div>
      <button type="submit">Edit</button>
    </form>
  </div>
</body>

</html>