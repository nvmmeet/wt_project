document.addEventListener('DOMContentLoaded', function() {
    fetchRandomResults();

    document.getElementById('search-input').addEventListener('input', function() {
        const query = this.value;
        fetchSearchResults(query);
    });
});

function fetchRandomResults() {
    fetch('search.php')
        .then(response => response.json())
        .then(data => displayResults(data))
        .catch(error => console.error('Error:', error));
}

function fetchSearchResults(query) {
    fetch(`search.php?query=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => displayResults(data))
        .catch(error => console.error('Error:', error));
}

function displayResults(data) {
    const searchResultsContainer = document.getElementById('search-results');
    searchResultsContainer.innerHTML = '';

    if (data.songs.length > 0) {
        const songsSection = document.createElement('div');
        const songsHeading = document.createElement('h3');
        songsHeading.textContent = 'Songs';
        songsSection.appendChild(songsHeading);

        data.songs.forEach(song => {
            const songElement = document.createElement('div');
            songElement.className = 'search-result';
            songElement.innerHTML = `
                <a href="" class="image-50">
                    <div><img src="${song.song_pic_url}" alt="search-song" /></div>
                    <div><span>${song.song_name}</span><p>${song.username}</p></div>
                </a>`;
            songsSection.appendChild(songElement);
        });

        searchResultsContainer.appendChild(songsSection);
    }

    if (data.albums.length > 0) {
        const albumsSection = document.createElement('div');
        const albumsHeading = document.createElement('h3');
        albumsHeading.textContent = 'Albums';
        albumsSection.appendChild(albumsHeading);

        data.albums.forEach(album => {
            const albumElement = document.createElement('div');
            albumElement.className = 'search-result';
            albumElement.innerHTML = `
                <a href="album.php">
                    <div><img src="${album.album_pic_url}" alt="search-album-image" /></div>
                    <div><span>${album.album_name}</span></div>
                </a>`;
            albumsSection.appendChild(albumElement);
        });

        searchResultsContainer.appendChild(albumsSection);
    }

    if (data.artists.length > 0) {
        const artistsSection = document.createElement('div');
        const artistsHeading = document.createElement('h3');
        artistsHeading.textContent = 'Artists';
        artistsSection.appendChild(artistsHeading);

        data.artists.forEach(artist => {
            const artistElement = document.createElement('div');
            artistElement.className = 'search-result';
            artistElement.innerHTML = `
                <a href="artist.php">
                    <div><img src="${artist.profile_pic_url}" alt="search-artist-image" /></div>
                    <div><span>${artist.username}</span></div>
                </a>`;
            artistsSection.appendChild(artistElement);
        });

        searchResultsContainer.appendChild(artistsSection);
    }
}
