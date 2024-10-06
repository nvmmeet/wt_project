<?php
include "includes/sessions/false.php";
include "includes/config.php";

$album_id = intval($_GET['album']);
if (!$album_id) {
    $error = "Bad Request!";
}

$albumQuery = $pdo->prepare("
    SELECT 
        a.album_id,
        a.album_name,
        a.album_pic_url,
        s.song_id,
        s.song_name,
        s.song_pic_url
    FROM 
        albums a
    LEFT JOIN 
        album_songs als ON a.album_id = als.album_id
    LEFT JOIN 
        songs s ON als.song_id = s.song_id
    WHERE 
        a.album_id = :album_id
");
$albumQuery->execute(["album_id" => $album_id]);
$albumData = $albumQuery->fetchAll(PDO::FETCH_ASSOC);

if (empty($albumData)) {
    $error = "Album Not Found!";
} else {
    $album = reset($albumData);
}
?>

<html>

<head>
    <title><?= htmlspecialchars($album['album_name']) ?> | Beatwaves</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/sidebar.css" />
    <link rel="stylesheet" href="css/main.css" />
</head>

<body>
    <?php include "includes/sidebar.php"; ?>
    <main>
        <?php if (isset($error)): ?>
            <h1 class="error"><?= htmlspecialchars($error) ?></h1>
        <?php else: ?>
            <section class="album-section">
                <div class="album-header">
                    <img src="<?= htmlspecialchars($album['album_pic_url']) ?>" alt="Album Cover" style="border-radius: 20px;" />
                    <h2><?= htmlspecialchars($album['album_name']) ?></h2>
                    <a href="#" class="play-button"><i class="bi bi-play-circle-fill"></i> Play</a>
                </div>

                <h2>Songs</h2>
                <table class="songs">
                    <tr>
                        <th>#</th>
                        <th>Song Name</th>
                        <th>Play</th>
                    </tr>
                    <?php foreach ($albumData as $song): ?>
                        <?php if ($song['song_id']): ?>
                            <tr>
                                <td><img src="" alt="song_pic"></td>
                                <td><?= htmlspecialchars($song['song_name']) ?></td>
                                <td><a href="#" class="play-button"><i class="bi bi-caret-right-fill"></i> Play</a></td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </table>
            </section>
        <?php endif; ?>
    </main>
    <?php include "includes/searchbar.php" ?>
</body>

</html>