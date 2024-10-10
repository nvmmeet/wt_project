<?php
session_start();
if (!isset($_SESSION["admin_username"])) {
    header("Location: login.php");
    exit;
}

include "../includes/config.php";

$songQuery = $pdo->prepare("
    SELECT 
        s.song_id, 
        s.song_name, 
        u.username,
        s.song_pic_url
    FROM 
        songs s
    JOIN 
        users u ON s.user_id = u.user_id
");
$songQuery->execute();
$songs = $songQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<html>

<head>
    <title>Songs| ADMIN | Beatwaves</title>
    <link rel="stylesheet" href="admin.css" />
</head>

<body>
    <?php include "sidebar.php" ?>
    <main>
        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'song_deleted'): ?>
            <p style="color: green;">Song deleted successfully.</p>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th data-column="song_id" data-order="desc">ID</th>
                    <th data-column="song_name" data-order="desc">Song Name</th>
                    <th data-column="username" data-order="desc">Username</th>
                    <th data-column="action" data-order="desc">Action</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <?php foreach ($songs as $song): ?>
                    <tr>
                        <td>
                            <?php
                            $songUrl = $song['song_pic_url'] !== 'default' ? "../uploads/images/songs/" . htmlspecialchars($song['song_pic_url']) : "../uploads/images/songs/emptysong.jpg";
                            ?>
                            <img src="<?= $songUrl ?>" alt="Song Image" style="width: 50px; height: 50px; border-radius: 50%;" />
                        </td>
                        <td><?= htmlspecialchars($song['song_id']) ?></td>
                        <td><?= htmlspecialchars($song['song_name']) ?></td>
                        <td><?= htmlspecialchars($song['username']) ?></td>
                        <td>
                            <form method="post" action="delete_song.php">
                                <input type="hidden" name="song_id" value="<?= htmlspecialchars($song['song_id']) ?>" />
                                <button type="submit" name="delete_song" onclick="return confirm('Are you sure you want to delete this song?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>

</html>