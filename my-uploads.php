<?php
include "includes/sessions/false.php";
include "includes/config.php";
$username = $_SESSION['username'];

$songsQuery = $pdo->prepare("
    SELECT song_id, song_name, song_pic_url 
    FROM songs 
    WHERE user_id = (SELECT user_id FROM users WHERE username = :username)
");
$songsQuery->execute(['username' => $username]);
$songs = $songsQuery->fetchAll();

$albumsQuery = $pdo->prepare("
    SELECT album_id, album_name, album_pic_url 
    FROM albums 
    WHERE user_id = (SELECT user_id FROM users WHERE username = :username)
");
$albumsQuery->execute(['username' => $username]);
$albums = $albumsQuery->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];

    if (empty($_FILES['song_url']['name'])) {
        $errors[] = 'Please upload a music file.';
    }

    if (empty($_POST['song_name'])) {
        $errors[] = 'Please provide a song name.';
    }

    $song_album = $_POST['song_album'] !== 'none' ? $_POST['song_album'] : null;
}
?>

<html>

<head>
    <title>My Uploads | Beatwaves</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/sidebar.css" />
    <link rel="stylesheet" href="css/main.css" />
    <link rel="stylesheet" href="css/cards.css" />
    <link rel="stylesheet" href="css/modals.css" />
</head>

<body>
    <?php include "includes/sidebar.php"; ?>

    <main class="w75">
        <h1 class="breadcrumb">
            Your Profile
            <input type="checkbox" id="breadcrumb">
            <label for="breadcrumb" class="breadcrumb-actions">
                <i class="bi bi-plus" style="font-size:30px;"></i>
            </label>
            <div class="breadcrumb-dropdown my-upload">
                <div class="dropdown-item" onclick="toggleModal()">
                    <span>Upload Music</span>
                </div>
                <div class="dropdown-item" onclick="toggleModal2()">
                    <span>Upload Album</span>
                </div>
            </div>
        </h1>

        <section>
            <div class="section-upper">
                <h2>My Songs</h2>
            </div>
            <div class="song-cards">
                <?php if (!empty($songs)): ?>
                    <?php foreach ($songs as $song): ?>
                        <div class='song-card'>
                            <?php $songUrl = $song['song_pic_url'] === 'default' ? "uploads/images/songs/emptysong.jpg" : "uploads/images/songs/" . $song["song_pic_url"] ?>
                            <img src='<?= $songUrl ?>' alt='song' />
                            <span><?= htmlspecialchars($song['song_name']) ?></span>
                            <form method="post" action="delete_song.php">
                                <input type="hidden" name="song_id" value="<?= htmlspecialchars($song['song_id']) ?>" />
                                <button type="submit" class="delete" name="delete_song" onclick="return confirm('Delete <?= $song['song_name'] ?>?');"><i class="bi bi-trash"></i></button>
                            </form>
                            <div class="play-button">
                                <i class="bi bi-caret-right"></i>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <?php include "includes/nothing.php" ?>
                <?php endif; ?>
            </div>

        </section>
        <section>
            <div class="section-upper">

                <h2>My Albums</h2>
            </div>
            <div class="square-cards">
                <?php if (!empty($albums)): ?>
                    <?php foreach ($albums as $album): ?>
                        <?php $albumUrl = $album['album_pic_url'] === 'default' ? "uploads/images/albums/emptyalbum.jpg" : "uploads/images/albums/" . $album["album_pic_url"] ?>
                        <a href="my-album.php?album=<?= $album['album_id'] ?>" class="card">
                            <img src="<?= $albumUrl ?>" alt='album' />
                            <span><?= htmlspecialchars($album['album_name']) ?></span>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <?php include "includes/nothing.php" ?>
                <?php endif; ?>
            </div>
        </section>
    </main>
    <div class="modal-bg" id="modal-bg">
        <div class="modal upload">
            <i class="bi bi-x-lg close-modal" onclick="toggleModal()"></i>
            <form action="uploadsong.php" method="POST" enctype="multipart/form-data" name="song_form" onsubmit="return validateForm()">
                <?php $defaulturl = "uploads/images/songs/emptysong.jpg" ?>
                <img src='<?= $defaulturl ?>' alt="upload-pic" id="image_preview" class="rad15">
                <input type="file" name="song_image_upload" accept="image/*" id="song_image_upload" onchange="changeImage(event)">
                <label for="song_image_upload">Change Image</label>
                <p class="song_url">No Songs Selected</p>
                <input type="file" name="song_url" accept=".mp3, .wav, .ogg" id="song_url" onchange="showSongName()">
                <label for="song_url">Change Music</label>
                <input type="text" name="song_name" id="song_name" placeholder="Song Name...">
                <select name="song_album" id="song_album">
                    <option value="none">No Album</option>
                    <?php foreach ($albums as $album): ?>
                        <option value="<?= $album['album_id'] ?>"><?= htmlspecialchars($album['album_name']) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="upload_btn">Upload</button>
            </form>
        </div>
    </div>

    <div class="modal-bg modal-bg2">
        <div class="modal upload">
            <i class="bi bi-x-lg close-modal" onclick="toggleModal2()"></i>
            <form action="uploadalbum.php" method="POST" enctype="multipart/form-data" name="song_form" onsubmit="return validateForm2()">
                <?php $defaulturl = "uploads/images/albums/emptyalbum.jpg" ?>
                <img src='<?= $defaulturl ?>' alt="upload-pic" id="image_preview2" class="rad15">
                <input type="file" name="album_image_upload" accept="image/*" id="album_image_upload" onchange="changeImage2(event)">
                <label for="album_image_upload">Change Image</label>
                <input type="text" name="album_name" id="album_name" placeholder="Album Name...">
                <button type="submit" class="upload_btn">Upload</button>
            </form>
        </div>
    </div>
    <script src="js/song.js"></script>
    <script>
        function toggleModal() {
            let modalBg = document.querySelector(".modal-bg");
            modalBg.classList.toggle("active");
        }

        function toggleModal2() {
            let modalBg = document.querySelector(".modal-bg.modal-bg2");
            modalBg.classList.toggle("active");
        }

        function validateForm() {
            const songUrl = document.getElementById("song_url").value;
            const songName = document.getElementById("song_name").value;
            if (!songUrl) {
                return false;
            }
            if (!songName) {
                return false;
            }
            return true;
        }

        function validateForm2() {
            const albumName = document.getElementById("album_name").value;
            if (!albumName) {
                return false;
            }
            return true;
        }

        function showSongName() {
            const songInput = document.getElementById('song_url');

            const songFileName = songInput.files.length > 0 ? songInput.files[0].name : 'No file selected';

            document.querySelector('p.song_url').textContent = songFileName;
        }

        function changeImage(event) {
            const imagePreview = document.getElementById('image_preview');
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                imagePreview.style.display = 'none';
            }
        };

        function changeImage2(event) {
            const imagePreview = document.getElementById('image_preview2');
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                }
                reader.readAsDataURL(file);
            } else {
                imagePreview.style.display = 'none';
            }
        };
    </script>
</body>

</html>