<?php
include "includes/sessions/false.php";
include "includes/config.php";

$username = $_SESSION['username'];

if (isset($_FILES['image_upload']) && $_FILES['image_upload']['error'] === UPLOAD_ERR_OK) {

    $targetDir = "uploads/images/users/";
    $fileName = basename($_FILES['image_upload']['name']);
    $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($fileType, $allowedTypes)) {

        $query = $pdo->prepare("SELECT profile_pic_url FROM users WHERE username = :username");
        $query->execute(['username' => $username]);
        $currentProfilePic = $query->fetch()['profile_pic_url'];

        if ($currentProfilePic != "default" && file_exists($targetDir . $currentProfilePic)) {
            unlink($targetDir . $currentProfilePic);
        }

        $newFileName = pathinfo($fileName, PATHINFO_FILENAME);
        $counter = 1;
        while (file_exists($targetDir . $newFileName . '.' . $fileType)) {
            $newFileName = pathinfo($fileName, PATHINFO_FILENAME) . '_' . $counter;
            $counter++;
        }

        $newFileName .= '.' . $fileType;

        if (move_uploaded_file($_FILES['image_upload']['tmp_name'], $targetDir . $newFileName)) {

            $updateQuery = $pdo->prepare("UPDATE users SET profile_pic_url = :profile_pic_url WHERE username = :username");
            $updateQuery->execute([
                'profile_pic_url' => htmlspecialchars($newFileName),
                'username' => $username
            ]);

            header("Location: profile.php");
            exit();
        } else {
            echo "File upload failed.";
        }
    } else {
        echo "Invalid file type. Only JPG, JPEG, PNG, and GIF files are allowed.";
    }
} else {
    echo "No file uploaded or there was an error.";
}
