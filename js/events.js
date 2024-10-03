function toggleNav() {
  let sidebar = document.querySelector("aside.sidebar");
  let searchbar = document.querySelector("aside.search-sidebar");
  sidebar.classList.toggle("active");
  if (searchbar.classList.contains("active")) {
    searchbar.classList.remove("active");
  }
}

function toggleSearchSidebar() {
  let sidebar = document.querySelector("aside.sidebar");
  let searchbar = document.querySelector("aside.search-sidebar");
  searchbar.classList.toggle("active");
  if (sidebar.classList.contains("active")) {
    sidebar.classList.remove("active");
  }
}
