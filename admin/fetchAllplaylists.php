<?php
session_start();
if (!isset($_SESSION["admin_username"])) {
    header("Location: login.php");
    exit();
}

include "../includes/config.php";

$playlistQuery = $pdo->prepare("
    SELECT 
        p.playlist_id, 
        p.playlist_name, 
        p.playlist_pic_url, 
        u.username, 
        (SELECT COUNT(*) FROM playlist_songs WHERE playlist_id = p.playlist_id) AS song_count
    FROM playlists p
    JOIN users u ON p.user_id = u.user_id
");
$playlistQuery->execute();
$playlists = $playlistQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<html>

<head>
    <title>Playlists | ADMIN | Beatwaves</title>
    <link rel="stylesheet" href="admin.css" />
</head>

<body>
    <?php include "sidebar.php" ?>
    <main>
        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'playlist_deleted'): ?>
            <p style="color: green;">Playlist deleted successfully.</p>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>ID</th>
                    <th>Playlist Name</th>
                    <th>Username</th>
                    <th>Song Count</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($playlists as $playlist): ?>
                    <tr>
                        <td>
                            <img src="../uploads/images/playlists/<?= $playlist['playlist_pic_url'] === "default" ? "emptyplaylist.jpg" : $playlist['playlist_pic_url'] ?>" alt="Playlist Image" style="width: 50px; height: 50px; border-radius: 50%;" />
                        </td>
                        <td><?= htmlspecialchars($playlist['playlist_id']) ?></td>
                        <td><?= htmlspecialchars($playlist['playlist_name']) ?></td>
                        <td><?= htmlspecialchars($playlist['username']) ?></td>
                        <td><?= htmlspecialchars($playlist['song_count']) ?></td>
                        <td>
                            <form method="post" action="delete_playlist.php">
                                <input type="hidden" name="playlist_id" value="<?= htmlspecialchars($playlist['playlist_id']) ?>" />
                                <button type="submit" name="delete_playlist" onclick="return confirm('Are you sure you want to delete this playlist and all associated songs?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>

</html>