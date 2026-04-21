<?php

$db = mysqli_connect("127.0.0.1", "root", "", "projectweek2")
      or die("Database verbinding mislukt");

$message = "";

// TOEVOEGEN
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $dag = $_POST["dag"];
    $dagdeel = $_POST["dagdeel"];
    $leeftijd = $_POST["leeftijd"];
    $naam = trim($_POST["naam"]);
    $beschrijving = trim($_POST["beschrijving"]);

    if (!empty($dag) && !empty($dagdeel) && !empty($leeftijd) && !empty($naam)) {

        $query = "INSERT INTO dagprogramma (dag, dagdeel, leeftijd, naam, beschrijving)
                  VALUES (?, ?, ?, ?, ?)";

        $stmt = $db->prepare($query);

        if (!$stmt) {
            die("Prepare fout: " . $db->error);
        }

        $stmt->bind_param("sssss", $dag, $dagdeel, $leeftijd, $naam, $beschrijving);
        $stmt->execute();

        $message = "Activiteit toegevoegd!";
        $stmt->close();

    } else {
        $message = "Vul alle verplichte velden in.";
    }
}

// OPHALEN
$query = "SELECT * FROM dagprogramma ORDER BY dag, dagdeel, leeftijd";
$result = $db->query($query);

$activiteiten = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $activiteiten[] = $row;
    }
}

$db->close();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Dagprogramma - KVW Gorinchem</title>
</head>
<body>

<header>
    <h1>Dagprogramma</h1>
</header>

<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="dagprogramma.php">Dagprogramma</a></li>
        <li><a href="nieuws.php">Nieuws</a></li>
    </ul>
</nav>

<main>

<section>
    <h2>Activiteit toevoegen</h2>

    <form method="POST">

        <label>Dag:</label><br>
        <select name="dag" required>
            <option>Maandag</option>
            <option>Dinsdag</option>
            <option>Woensdag</option>
            <option>Donderdag</option>
            <option>Vrijdag</option>
        </select><br><br>

        <label>Dagdeel:</label><br>
        <select name="dagdeel" required>
            <option>Ochtend</option>
            <option>Middag</option>
        </select><br><br>

        <label>Leeftijd:</label><br>
        <select name="leeftijd" required>
            <option>Kleuters</option>
            <option>Onderbouw</option>
            <option>Bovenbouw</option>
        </select><br><br>

        <input type="text" name="naam" placeholder="Naam activiteit" maxlength="50" required><br><br>

        <textarea name="beschrijving" placeholder="Beschrijving" rows="4"></textarea><br><br>

        <input type="submit" value="Toevoegen">

    </form>

    <p><?php echo $message; ?></p>
</section>

<hr>

<section>
    <h2>Overzicht dagprogramma</h2>

    <?php if (count($activiteiten) > 0): ?>
        <ul>
            <?php foreach ($activiteiten as $a): ?>
                <li>
                    <strong><?php echo htmlspecialchars($a['dag']); ?></strong>
                    (<?php echo htmlspecialchars($a['dagdeel']); ?> -
                    <?php echo htmlspecialchars($a['leeftijd']); ?>)<br>

                    <b><?php echo htmlspecialchars($a['naam']); ?></b><br>
                    <?php echo nl2br(htmlspecialchars($a['beschrijving'])); ?>
                </li>
                <br>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Nog geen activiteiten toegevoegd.</p>
    <?php endif; ?>

</section>

</main>

<footer>
    <p>&copy; 2026 KVW Gorinchem</p>
</footer>

</body>
</html>