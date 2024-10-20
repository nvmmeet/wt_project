document.addEventListener("DOMContentLoaded", function () {
  fetchRandomResults();

  document
    .getElementById("search-input")
    .addEventListener("input", function () {
      const query = this.value;
      fetchSearchResults(query);
    });
});

function fetchRandomResults() {
  fetch("search.php")
    .then((response) => response.json())
    .then((data) => displayResults(data))
    .catch((error) => console.error("Error:", error));
}

function fetchSearchResults(query) {
  fetch(`search.php?query=${encodeURIComponent(query)}`)
    .then((response) => response.json())
    .then((data) => displayResults(data))
    .catch((error) => console.error("Error:", error));
}

function displayResults(data) {
  const searchResultsContainer = document.getElementById("search-results");
  searchResultsContainer.innerHTML = "";
  
  if (data.albums.length > 0) {
    const albumsSection = document.createElement("div");
    const albumsHeading = document.createElement("h3");
    albumsHeading.textContent = "Albums";
    albumsSection.appendChild(albumsHeading);

    data.albums.forEach((album) => {
      const albumElement = document.createElement("div");
      albumElement.className = "search-result";
      albumImage =
        album.album_pic_url === "default"
          ? "emptyalbum.jpg"
          : `${album.album_pic_url}`;
      albumElement.innerHTML = `
          <a href="album.php?album=${album.album_id}">
          <div><img src="uploads/images/albums/${albumImage}" alt="search-album-image" /></div>
          <div><span>${album.album_name}</span></div>
          </a>`;
      albumsSection.appendChild(albumElement);
    });

    searchResultsContainer.appendChild(albumsSection);
  }

  if (data.artists.length > 0) {
    const artistsSection = document.createElement("div");
    const artistsHeading = document.createElement("h3");
    artistsHeading.textContent = "Artists";
    artistsSection.appendChild(artistsHeading);

    data.artists.forEach((artist) => {
      const artistElement = document.createElement("div");
      artistElement.className = "search-result";
      artistImage =
        artist.profile_pic_url === "default"
          ? "emptyuser.jpg"
          : `${artist.profile_pic_url}`;
      artistElement.innerHTML = `
            <a href="artist.php?artist=${artist.user_id}" class="image-50">
            <div><img src="uploads/images/users/${artistImage}" alt="search-artist-image" /></div>
                    <div><span>${artist.username}</span></div>
                    </a>`;
      artistsSection.appendChild(artistElement);
    });

    searchResultsContainer.appendChild(artistsSection);
  }
}
