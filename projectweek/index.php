<?php
$db = mysqli_connect("127.0.0.1", "root", "", "projectweek2")
      or die("DB fout");

// laatste nieuws ophalen
$query = "SELECT titel, news, datum FROM news ORDER BY datum DESC LIMIT 1";
$result = $db->query($query);

$laatsteNieuws = null;
if ($result && $result->num_rows > 0) {
    $laatsteNieuws = $result->fetch_assoc();
}

$db->close();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>KVW Gorinchem 2026 - Middeleeuwen</title>
</head>
<body>

<header>
    <h1>Welkom bij KVW Gorinchem 2026!</h1>
    <h2>Thema: Middeleeuwen 🏰</h2>
</header>

<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="dagprogramma.php">Dagprogramma</a></li>
        <li><a href="#">Foto's & Video's</a></li>
        <li><a href="nieuws.php">Nieuws</a></li>
    </ul>
</nav>

<main>

<section>
    <h3>Welkom!</h3>
    <p>
        Van 20 t/m 24 juli gaan we terug naar de Middeleeuwen!
        Beleef avonturen met ridders, kastelen en spannende spellen.
    </p>
</section>

<section>
    <h3>Blijf op de hoogte</h3>
    <form method="POST" action="#">
        <input type="email" name="email" placeholder="E-mailadres" required>
        <input type="submit" value="Aanmelden">
    </form>
</section>

<section>
    <h3>Laatste nieuws</h3>

    <?php if ($laatsteNieuws): ?>
        <p>
            <strong><?php echo htmlspecialchars($laatsteNieuws['titel']); ?></strong><br>
            <?php echo htmlspecialchars($laatsteNieuws['news']); ?><br>
            <small><?php echo $laatsteNieuws['datum']; ?></small>
        </p>
    <?php else: ?>
        <p>Er is nog geen nieuws.</p>
    <?php endif; ?>

    <p><a href="nieuws.php">Bekijk alle nieuwsberichten</a></p>
</section>

<section>
    <h3>Afbeelding</h3>
    <img src="download.jpg" alt="Middeleeuwen">
</section>

</main>

<footer>
    <p>&copy; 2026 KVW Gorinchem</p>
</footer>

</body>
</html>