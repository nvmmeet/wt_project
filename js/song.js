let songQueue = [];
let currentSongIndex = 0;
let isPlaying = false;
const audioElement = document.getElementById('audio');

// Set the initial song queue and load the first song
function setSongQueue(songs) {
    songQueue = songs;
    currentSongIndex = 0;
    loadSong(songQueue[currentSongIndex]);
    loadRandomSongs(songQueue.length); // Load random songs if needed
}

// Load a specific song into the audio player
function loadSong(song) {
    if (song.url) {
        document.getElementById('song-name').innerText = song.song_name;
        document.getElementById('song-image').src = song.image;
        audioElement.src = song.url;
    } else {
        console.log('Song URL not found');
    }
}


// Toggle play/pause functionality
function playPauseSong() {
    if (isPlaying) {
        audioElement.pause();
        document.getElementById('play-pause-btn').innerHTML = '<i class="bi bi-play-fill"></i>';
        isPlaying = false;
    } else {
        audioElement.play();
        document.getElementById('play-pause-btn').innerHTML = '<i class="bi bi-pause-fill"></i>';
        isPlaying = true;
    }
}

// Play the next song in the queue
function playNextSong() {
    if (songQueue.length === 0) {
        console.log('No songs in the queue');
        return;
    }

    if (currentSongIndex < songQueue.length - 1) {
        currentSongIndex++;
        loadSong(songQueue[currentSongIndex]);
        if (!isPlaying) playPauseSong(); // Only play if the audio is paused
    } else {
        console.log('Reached the end of the queue');
        loadRandomSongs(songQueue.length); // Load more random songs if needed
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
        if (!isPlaying) playPauseSong(); // Only play if the audio is paused
    } else {
        console.log('Reached the beginning of the queue');
    }
}


// Play an album's songs
function playAlbum(albumId) {
    fetch(`get_album_songs.php?album=${albumId}`)
        .then(response => response.json())
        .then(songs => {
            setSongQueue(songs);
            playPauseSong(); // Start playing after setting the queue
        });
}

function playPlaylist(albumId) {
    fetch(`get_playlist_songs.php?album=${albumId}`)
        .then(response => response.json())
        .then(songs => {
            setSongQueue(songs);
            playPauseSong(); // Start playing after setting the queue
        });
}

// Play a single song from a song card
function playSongFromCard(element) {
    const song = {
        id: element.getAttribute('data-song-id'),
        song_name: element.getAttribute('data-song-name'),
        image: element.getAttribute('data-song-image'),
        url: element.getAttribute('data-song-url')
    };

    setSongQueue([song]);  // Only the clicked song is in the queue initially
    playPauseSong(); // Play it immediately
}

// Load random songs to fill the queue up to 10 songs
function loadRandomSongs(queueCount) {
    fetch(`get_random_song.php?queue_count=${queueCount}`)
        .then(response => response.json())
        .then(randomSongs => {
            songQueue.push(...randomSongs);
            console.log(`Loaded ${randomSongs.length} random songs.`);
        });
}


// Event listeners for play/pause, next, and previous buttons
document.getElementById('play-pause-btn').addEventListener('click', playPauseSong);
document.getElementById('next-btn').addEventListener('click', playNextSong);
document.getElementById('prev-btn').addEventListener('click', playPrevSong);
