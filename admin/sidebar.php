<div class="sidebar">
    <div class="links">
        <a href="index.php">Users</a>
        <a href="fetchAllsongs.php">Songs</a>
        <a href="fetchAllalbums.php">Albums</a>
        <a href="fetchAllplaylists.php">Playlists</a>
        <a href="logout.php">Log Out</a>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const currentPage = window.location.pathname;

        const links = document.querySelectorAll(".links a");

        links.forEach(link => {
            if (link.href.includes(currentPage)) {
                link.classList.add("active");
            } else {
                link.classList.remove("active");
            }
        });
    });
</script>