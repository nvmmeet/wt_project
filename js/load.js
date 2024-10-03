document.addEventListener("DOMContentLoaded", () => {
  const sidebar = `
    <aside class="sidebar nav">
      <div class="sidebar-top">
        <a href="home.html">
          <img src="images/logo.jpg" alt="logo" />
          <h2>Beatwaves</h2>
        </a>
        <i class="bi bi-list center" onclick="toggleNav()"></i>
      </div>
      <div class="links">
        <div class="link-group">
          <h3>
            <i class="bi bi-compass"></i>
            <span>Explore</span>
          </h3>
          <a href="home.html" class="active">
            <span>Home</span>
          </a>
          <a href="albums.html">
            <span>Albums</span>
          </a>
          <a href="artists.html">
            <span>Artists</span>
          </a>
        </div>
        <div class="link-group">
          <h3>
            <i class="bi bi-collection-play"></i>
            <span>Library</span>
          </h3>
          <a href="favourites.html">
            <span>Favourites</span>
          </a>
          <a href="playlists.html">
            <span>Playlists</span>
          </a>
          <a href="fav-albums.html">
            <span>Fav Albums</span>
          </a>
          <a href="fav-artists.html">
            <span>Followed Artists</span>
          </a>
        </div>
        <div class="link-group">
          <h3>
            <i class="bi bi-person"></i>
            <span>Profile</span>
          </h3>
          <a href="profile.html" class="no-pad">
            <img src="images/pfp.jfif" alt="pfp" />
            <span>Meet Sanghvi</span>
          </a>
          <a href="uploads.html" class="no-pad">
          <i class="bi bi-box-arrow-up"></i>
            <span>My uploads</span>
          </a>
          <button class="no-pad">
            <i class="bi bi-box-arrow-left"></i>
            <span>Logout</span>
          </button>
        </div>
      </div>
    </aside>
  `;

  document.getElementById("use-sidebar").innerHTML = sidebar;

  function setActiveLink() {
    const currentPath = window.location.pathname.split("/").pop();
    const links = document.querySelectorAll(".sidebar a");

    links.forEach((link) => {
      if (link.getAttribute("href") === currentPath) {
        link.classList.add("active");
      } else {
        link.classList.remove("active");
      }
    });
  }

  setActiveLink();
});

document.addEventListener("DOMContentLoaded", () => {
  const searchSidebar = `
    <aside class="search-sidebar">
      <div class="search-sidebar-top center">
        <h2>Search</h2>
        <i class="bi bi-list close-btn center" onclick="toggleSearchSidebar()"></i>
      </div>
      <div class="search-bar">
        <i class="bi bi-search center"></i>
        <input type="text" placeholder="Search..." id="search-input" />
      </div>
      <div class="search-results" id="search-results">
        <!-- Dynamic search results will be injected here -->
      </div>
    </aside>
  `;

  document.getElementById("use-search").innerHTML = searchSidebar;

  const searchInput = document.getElementById("search-input");
  const searchResultsContainer = document.getElementById("search-results");

  const data = {
    songs: [
      { title: "Beatles", artist: "Travis Scott", image: "images/logo.jpg" },
      { title: "Let it Be", artist: "The Beatles", image: "images/logo.jpg" },
      { title: "Imagine", artist: "John Lennon", image: "images/logo.jpg" },
      { title: "Hey Jude", artist: "The Beatles", image: "images/logo.jpg" },
    ],
    artists: [
      { name: "The Beatles", image: "images/logo.jpg" },
      { name: "Travis Scott", image: "images/logo.jpg" },
      { name: "John Lennon", image: "images/logo.jpg" },
      { name: "Elton John", image: "images/logo.jpg" },
    ],
    albums: [
      { name: "Abbey Road", image: "images/logo.jpg" },
      { name: "Astroworld", image: "images/logo.jpg" },
      { name: "Imagine", image: "images/logo.jpg" },
      { name: "Yellow Submarine", image: "images/logo.jpg" },
    ],
  };

  function getRandomItems(arr, num) {
    const shuffled = arr.sort(() => 0.5 - Math.random());
    return shuffled.slice(0, num);
  }

  function renderSearchResults(searchTerm) {
    const filteredSongs = data.songs.filter((song) =>
      song.title.toLowerCase().includes(searchTerm.toLowerCase())
    );
    const filteredArtists = data.artists.filter((artist) =>
      artist.name.toLowerCase().includes(searchTerm.toLowerCase())
    );
    const filteredAlbums = data.albums.filter((album) =>
      album.name.toLowerCase().includes(searchTerm.toLowerCase())
    );

    searchResultsContainer.innerHTML = "";

    function renderRandomSection(title, items, limit) {
      const randomItems = getRandomItems(items, limit);
      if (randomItems.length > 0) {
        const section = document.createElement("div");
        section.classList.add("search-result");
        section.innerHTML = `<h3>${title}</h3>`;
        randomItems.forEach((item) => {
          const itemHTML = `
            <a href="${title.toLowerCase()}.html" class="${
            title === "Songs" ? "image-50" : ""
          }">
              <div><img src="${
                item.image
              }" alt="search-${title.toLowerCase()}-image" /></div>
              <div><span>${item.title || item.name}</span><p>${
            item.artist || ""
          }</p></div>
            </a>`;
          section.innerHTML += itemHTML;
        });
        searchResultsContainer.appendChild(section);
      }
    }

    if (searchTerm) {
      if (filteredSongs.length > 0) {
        const songSection = document.createElement("div");
        songSection.classList.add("search-result");
        songSection.innerHTML = `<h3>Songs</h3>`;
        filteredSongs.forEach((song) => {
          const songItem = `
            <a href="song.html" >
              <div><img src="${song.image}" alt="search-song-image" /></div>
              <div><span>${song.title}</span><p>${song.artist}</p></div>
            </a>`;
          songSection.innerHTML += songItem;
        });
        searchResultsContainer.appendChild(songSection);
      } else {
        renderRandomSection("Songs", data.songs, 2);
      }

      if (filteredArtists.length > 0) {
        const artistSection = document.createElement("div");
        artistSection.classList.add("search-result");
        artistSection.innerHTML = `<h3>Artists</h3>`;
        filteredArtists.forEach((artist) => {
          const artistItem = `
            <a href="artist.html">
              <div><img src="${artist.image}" alt="search-artist-image" /></div>
              <div><span>${artist.name}</span></div>
            </a>`;
          artistSection.innerHTML += artistItem;
        });
        searchResultsContainer.appendChild(artistSection);
      } else {
        renderRandomSection("Artists", data.artists, 2);
      }

      if (filteredAlbums.length > 0) {
        const albumSection = document.createElement("div");
        albumSection.classList.add("search-result");
        albumSection.innerHTML = `<h3>Albums</h3>`;
        filteredAlbums.forEach((album) => {
          const albumItem = `
            <a href="album.html">
              <div><img src="${album.image}" alt="search-album-image" /></div>
              <div><span>${album.name}</span></div>
            </a>`;
          albumSection.innerHTML += albumItem;
        });
        searchResultsContainer.appendChild(albumSection);
      } else {
        renderRandomSection("Albums", data.albums, 2);
      }
    } else {
      renderRandomSection("Songs", data.songs, 2);
      renderRandomSection("Artists", data.artists, 2);
      renderRandomSection("Albums", data.albums, 2);
    }
  }

  searchInput.addEventListener("input", (e) => {
    const searchTerm = e.target.value;
    renderSearchResults(searchTerm);
  });

  renderSearchResults("");
});
