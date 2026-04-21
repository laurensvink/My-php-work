<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Koninklijk Paleis - KVW Gorinchem</title>
    <link rel="stylesheet" href="css/styles.css?v=2">
</head>
<body>

<h1>⚔️ Welkom in het Koninklijke Paleis, <?php echo htmlspecialchars($username); ?>! ⚔️</h1>

<nav>
    <ul>
        <li><a href="news.php">📜 Kronieken Beheren</a></li>
        <li><a href="dagprogramma.php">📅 Agenda Beheren</a></li>
        <li><a href="upload.php">🖼️ Kunstwerken Uploaden</a></li>
        <li><a href="photos.php">🏛️ Kunstcabinet Bekijken</a></li>
        <li><a href="newsletter.php">📧 Nieuwsbrief Abonnees</a></li>
        <li><a href="logout.php">🚪 Uit het Paleis</a></li>
    </ul>
</nav>

<p>Gij zijt de bewaarder der Koninklijke Schatten...</p>

</body>
</html>