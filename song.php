<div class="song-container">
    <div class="song-image">
        <img id="song-image" src="song_image.jpg" alt="Song Image">
    </div>
    <div class="song-details">
        <span class="song-name" id="song-name">Song Name</span>
        <span class="artist-name" id="artist-name">Artist Name</span>
    </div>
    <div class="controls">
        <button id="prev-btn"><i class="bi bi-skip-backward-fill"></i></button>
        <button id="play-pause-btn"><i class="bi bi-play-fill" id="play-pause-icon"></i></button>
        <button id="next-btn"><i class="bi bi-skip-forward-fill"></i></button>
    </div>
    <div class="progress-container">
        <input type="range" id="progress-bar" value="0" min="0" max="100">
    </div>
    <audio id="audio" src="song.mp3"></audio> <!-- Source is updated dynamically -->

</div>