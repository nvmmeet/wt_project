<?php
session_start();
if (!isset($_SESSION["admin_username"])) {
    header("Location: login.php");
    exit();
}

include "../includes/config.php";

$albumQuery = $pdo->prepare("
    SELECT 
        a.album_id, 
        a.album_name, 
        a.album_pic_url, 
        u.username, 
        (SELECT COUNT(*) FROM album_songs WHERE album_id = a.album_id) AS song_count
    FROM albums a
    JOIN users u ON a.user_id = u.user_id
");
$albumQuery->execute();
$albums = $albumQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<html>

<head>
    <title>Albums | ADMIN | Beatwaves</title>
    <link rel="stylesheet" href="admin.css" />
</head>

<body>
    <?php include "sidebar.php" ?>
    <main>
        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'album_deleted'): ?>
            <p style="color: green;">Album deleted successfully.</p>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>ID</th>
                    <th>Album Name</th>
                    <th>Username</th>
                    <th>Song Count</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($albums as $album): ?>
                    <tr>
                        <td>
                            <?php
                            $albumUrl = $album['album_pic_url'] !== 'default' ? "../uploads/images/albums/" . htmlspecialchars($album['album_pic_url']) : "../uploads/images/albums/emptyalbum.jpg";
                            ?>
                            <img src="<?= $albumUrl ?>" alt="Album Image" style="width: 50px; height: 50px; border-radius: 50%;" />
                        </td>
                        <td><?= htmlspecialchars($album['album_id']) ?></td>
                        <td><?= htmlspecialchars($album['album_name']) ?></td>
                        <td><?= htmlspecialchars($album['username']) ?></td>
                        <td><?= htmlspecialchars($album['song_count']) ?></td>
                        <td>
                            <form method="post" action="delete_album.php">
                                <input type="hidden" name="album_id" value="<?= htmlspecialchars($album['album_id']) ?>" />
                                <button type="submit" name="delete_album" onclick="return confirm('Are you sure you want to delete this album and all associated songs?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>

</html>