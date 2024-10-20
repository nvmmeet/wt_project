let songQueue = [];
let currentSongIndex = 0;
let isPlaying = false;
let currentAlbumId = null;
let currentPlaylistId = null;
const audioElement = document.getElementById('audio');

function openSongContainer() {
    let songContainer = document.querySelector(".song-container");
    if (songQueue.length >= 1) {
        songContainer.classList.add("active");
    }
}

function playPauseSong() {
    if (isPlaying) {
        audioElement.pause();
    } else {
        audioElement.play();
    }
    isPlaying = !isPlaying;
    updatePlayPauseButton();
    updateAllPlayButtons();
}
 
function playNextSong() {
    if (songQueue.length === 0) {
        console.log('No songs in the queue');
        return;
    }

    if (currentSongIndex < songQueue.length - 1) {
        currentSongIndex++;
        loadSong(songQueue[currentSongIndex]);
        if (isPlaying) audioElement.play();
    } else {
        console.log('Reached the end of the queue');
        loadRandomSongs(songQueue.length); 
    }
}

function playPrevSong() {
    if (songQueue.length === 0) {
        console.log('No songs in the queue');
        return;
    }

    if (currentSongIndex > 0) {
        currentSongIndex--;
        loadSong(songQueue[currentSongIndex]);
        if (isPlaying) audioElement.play();
    } else {
        console.log('Reached the beginning of the queue');
    }
}

function playAlbum(albumId) {
    const wasPlaying = isPlaying;
    if (isPlaying) {
        audioElement.pause();
        isPlaying = false;
    }
    currentAlbumId = albumId;
    currentPlaylistId = null;
    fetch(`get_album_songs.php?album=${albumId}`)
        .then(response => response.json())
        .then(songs => {
            setSongQueue(songs);
            if (songs.length > 0) {
                openSongContainer();
                if (!wasPlaying) {
                    playPauseSong();
                }
            } else {
                console.log('No songs in this album');
            }
            updateAllPlayButtons();
        });
}

function setSongQueue(songs) {
    songQueue = songs;
    if (songs && songs.length > 0) {
        currentSongIndex = 0;
        loadSong(songQueue[currentSongIndex]);
    } else {
        closeSongContainer();
    }
}   

function loadSong(song) {
    if (song && song.url) { 
        document.getElementById('song-name').innerText = song.song_name;
        document.getElementById('artist-name').innerText = song.artist_name;
        document.getElementById('song-image').src = song.image;
        audioElement.src = song.url;
        if (isPlaying) {
            audioElement.play();
        }
    } else {
        console.log('Song URL not found');
    }
    updatePlayPauseButton();
    updateAllPlayButtons();
}

function updatePlayPauseButton() {
    const playPauseIcon = document.querySelector("#play-pause-icon");
    playPauseIcon.className = isPlaying ? "bi bi-pause-fill" : "bi bi-play-fill";
}

function updateAllPlayButtons() {
    if (currentAlbumId) {
        const albumPlayButton = document.querySelector(`.play-button[data-album-id="${currentAlbumId}"]`);
        if (albumPlayButton) {
            albumPlayButton.innerHTML = isPlaying ? '<i class="bi bi-pause-fill"></i>' : '<i class="bi bi-caret-right-fill"></i>';
        }
    }

    if (currentPlaylistId) {
        const playlistPlayButton = document.querySelector(`.play-button[data-playlist-id="${currentPlaylistId}"]`);
        if (playlistPlayButton) {
            playlistPlayButton.innerHTML = isPlaying ? '<i class="bi bi-pause-fill"></i>' : '<i class="bi bi-caret-right-fill"></i>';
        }
    }

    document.querySelectorAll('.play-button[data-song-id]').forEach(button => {
        const songId = button.getAttribute('data-song-id');
        if (songId === songQueue[currentSongIndex]?.id) {
            button.innerHTML = isPlaying ? '<i class="bi bi-pause-fill"></i>' : '<i class="bi bi-caret-right-fill"></i>';
        } else {
            button.innerHTML = '<i class="bi bi-caret-right-fill"></i>';
        }
    });
}

function playSongFromCard(element) {
    const song = {
        id: element.getAttribute('data-song-id'),
        song_name: element.getAttribute('data-song-name'),
        artist_name: element.getAttribute('data-artist-name'),
        image: element.getAttribute('data-song-image'),
        url: element.getAttribute('data-song-url')
    };

    setSongQueue([song]);
    playPauseSong();
}

function closeSongContainer() {
    let songContainer = document.querySelector(".song-container");
    songContainer.classList.remove("active");
    
    audioElement.pause();
    isPlaying = false;
    currentAlbumId = null;
    currentPlaylistId = null;
    songQueue = [];
    currentSongIndex = 0;
    
    updateAllPlayButtons();
}


function loadRandomSongs(queueCount) {
    fetch(`get_random_song.php?queue_count=${queueCount}`)
    .then(response => response.json())
    .then(randomSongs => {
        songQueue.push(...randomSongs);
        console.log(`Loaded ${randomSongs.length} random songs.`);
    });
}

document.getElementById('play-pause-btn').addEventListener('click', playPauseSong);
document.getElementById('next-btn').addEventListener('click', playNextSong);
document.getElementById('prev-btn').addEventListener('click', playPrevSong);
document.getElementById('close-song').addEventListener('click', closeSongContainer);
document.querySelectorAll(".play-button").forEach(item => {  
    item.addEventListener('click', openSongContainer);
});
document.querySelectorAll(".main-play-button").forEach(item => {  
    item.addEventListener('click', openSongContainer);
});

audioElement.addEventListener('ended', playNextSong);