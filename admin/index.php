<?php
session_start();
if (!isset($_SESSION["admin_username"])) {
    header("Location: login.php");
    exit();
}

include "../includes/config.php";

$userQuery = $pdo->prepare("
    SELECT 
        u.user_id, 
        u.name, 
        u.username, 
        u.dob, 
        u.profile_pic_url, 
        (SELECT COUNT(*) FROM songs WHERE user_id = u.user_id) AS songs, 
        (SELECT COUNT(*) FROM albums WHERE user_id = u.user_id) AS albums, 
        (SELECT COUNT(*) FROM playlists WHERE user_id = u.user_id) AS playlists
    FROM users u
");
$userQuery->execute();
$users = $userQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<html>

<head>
    <title>Users | ADMIN | Beatwaves</title>
    <link rel="stylesheet" href="admin.css" />
</head>

<body>
    <?php include "sidebar.php" ?>
    <main>
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>DOB</th>
                    <th>Songs</th>
                    <th>Albums</th>
                    <th>Playlists</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td>

                            <?php
                            $profilePicUrl = $user['profile_pic_url'] !== 'default' ? "../uploads/images/users/" . htmlspecialchars($user['profile_pic_url']) : "../uploads/images/users/emptyuser.jpg";
                            ?>
                            <img src="<?= $profilePicUrl ?>" alt="User Image" style="width: 50px; height: 50px; border-radius: 50%;" />
                        </td>
                        <td><?= htmlspecialchars($user['user_id']) ?></td>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['dob']) ?></td>
                        <td><?= htmlspecialchars($user['songs']) ?></td>
                        <td><?= htmlspecialchars($user['albums']) ?></td>
                        <td><?= htmlspecialchars($user['playlists']) ?></td>
                        <td>
                            <form method="post" action="deleteuser.php">
                                <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['user_id']) ?>" />
                                <button type="submit" name="delete_user" onclick="return confirm('Are you sure you want to delete this user and all associated data?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>

</html>