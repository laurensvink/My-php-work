<?php
session_start();

$host = "localhost";
$dbname = "projectweek2";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM photos ORDER BY datum DESC");
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Koninklijke Kunstcabinet - KVW Gorinchem</title>
    <link rel="stylesheet" href="css/styles.css?v=2">
</head>
<body>

<header>
    <h1>Koninklijke Kunstcabinet</h1>
    <h2>Foto's & Video's</h2>
</header>

<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="program.php">Dagprogramma</a></li>
        <li><a href="photos.php">Foto's & Video's</a></li>
        <li><a href="news.php">Nieuws</a></li>
        <?php if (isset($_SESSION['user_id'])): ?>
            <li><a href="admin.php">Beheer</a></li>
            <li><a href="logout.php">Uitloggen</a></li>
        <?php else: ?>
            <li><a href="login.php">Begeleiders Login</a></li>
        <?php endif; ?>
    </ul>
</nav>

<?php
if (isset($_SESSION["message"])) {
    echo "<div class='message'>" . $_SESSION["message"] . "</div>";
    unset($_SESSION["message"]);
}
?>

<?php if (isset($_SESSION['user_id'])): ?>
    <a class="upload-btn" href="upload.php">Upload foto</a>
<?php endif; ?>

<div class="gallery">
<?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<div class='photo'>";
        echo "<img class='gallery-img' src='" . htmlspecialchars($row["link"]) . "' alt='" . htmlspecialchars($row["titel"]) . "' onclick='openModal(this, \"" . htmlspecialchars(addslashes($row["titel"])) . "\")'>";
        echo "<p>" . htmlspecialchars($row["titel"]) . "</p>";

        if (isset($_SESSION['user_id'])) {
            echo "<form method='POST' action='delete.php' onsubmit='return confirm(\"Weet je zeker dat je deze foto wilt verwijderen?\")'>";
            echo "<input type='hidden' name='id' value='" . $row["id"] . "'>";
            echo "<button type='submit' class='delete-btn'>Verwijderen</button>";
            echo "</form>";
        }

        echo "</div>";
    }
} else {
    echo "Nog geen foto's geüpload.";
}
?>
</div>

<div id="imageModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>
        <img id="modalImage" class="modal-image" src="" alt="">
        <div id="modalTitle" class="modal-title"></div>
    </div>
</div>

<script>
function openModal(img, title) {
    const modal = document.getElementById("imageModal");
    const modalImg = document.getElementById("modalImage");
    const modalTitle = document.getElementById("modalTitle");
    
    modal.style.display = "flex";
    modalImg.src = img.src;
    modalTitle.textContent = title;
    document.body.style.overflow = "hidden";
}

function closeModal() {
    const modal = document.getElementById("imageModal");
    modal.style.display = "none";
    document.body.style.overflow = "auto";
}

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById("imageModal");
    modal.addEventListener("click", function(event) {
        if (event.target === modal) {
            closeModal();
        }
    });
    
    document.addEventListener("keydown", function(event) {
        if (event.key === "Escape") {
            closeModal();
        }
    });
});
</script>

<?php if (isset($_SESSION['user_id'])): ?>
    <div class="bottom-nav">
        <a href="admin.php" class="btn">Terug naar Paleis</a>
    </div>
<?php endif; ?>

</body>
</html>

<?php
$conn->close();
?>