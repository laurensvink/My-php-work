<?php

$dbhost = "127.0.0.1";
$dbuser = "root";
$dbpass = "";
$dbname = "projectweek2";

$dblink = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)
          or die("Niet mogelijk om te verbinden");

$message = "";

// INSERT (nieuw bericht toevoegen)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titel = trim($_POST["titel"]);
    $news = trim($_POST["news"]);

    if (!empty($titel) && !empty($news) && strlen($titel) <= 100 && strlen($news) <= 128) {
        $query = "INSERT INTO news (titel, news) VALUES (?, ?)";
        $stmt = $dblink->prepare($query);

        if(!$stmt){
            die("Prepare niet gelukt: " . $dblink->error);
        }

        $stmt->bind_param("ss", $titel, $news);
        $stmt->execute();

        $message = "Nieuws toegevoegd!";
        $stmt->close();
    } else {
        $message = "Controleer invoer (titel max 100, bericht max 128 tekens)";
    }
}

// SELECT (nieuws ophalen)
$query = "SELECT * FROM news ORDER BY datum DESC";
$result = $dblink->query($query);

$nieuwsLijst = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $nieuwsLijst[] = $row;
    }
}

$dblink->close();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Nieuws - KVW Gorinchem</title>
</head>
<body>

<header>
    <h1>Nieuws & Updates</h1>
</header>

<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="#">Dagprogramma</a></li>
        <li><a href="#">Foto's & Video's</a></li>
        <li><a href="nieuws.php">Nieuws</a></li>
    </ul>
</nav>

<main>

<section>
    <h2>Nieuw bericht toevoegen</h2>
    <form method="POST">
        <input type="text" name="titel" placeholder="Titel" maxlength="100" required><br><br>
        <input type="text" name="news" placeholder="Nieuwsbericht" maxlength="128" required><br><br>
        <input type="submit" value="Toevoegen">
    </form>
    <p><?php echo $message; ?></p>
</section>

<section>
    <h2>Alle nieuwsberichten</h2>

    <?php if (count($nieuwsLijst) > 0): ?>
        <ul>
            <?php foreach ($nieuwsLijst as $item): ?>
                <li>
                    <strong><?php echo htmlspecialchars($item['titel']); ?></strong><br>
                    <?php echo htmlspecialchars($item['news']); ?><br>
                    <small><?php echo $item['datum']; ?></small>
                </li>
                <br>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Er zijn nog geen nieuwsberichten.</p>
    <?php endif; ?>

</section>

</main>

<footer>
    <p>&copy; 2026 KVW Gorinchem</p>
</footer>

</body>
</html>