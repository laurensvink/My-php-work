<?php
session_start();

$db = mysqli_connect("127.0.0.1", "root", "", "projectweek2")
      or die("Database verbinding mislukt");

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");

    if (!empty($name) && !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Check if email already exists
        $check_query = "SELECT id FROM newsletter_subscribers WHERE email = ?";
        $check_stmt = $db->prepare($check_query);
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $message = "Dit e-mailadres is al ingeschreven!";
            $message_type = "error";
        } else {
            $query = "INSERT INTO newsletter_subscribers (name, email) VALUES (?, ?)";
            $stmt = $db->prepare($query);
            $stmt->bind_param("ss", $name, $email);
            
            if ($stmt->execute()) {
                $message = "Dank u voor uw inschrijving! U ontvangt nu alle nieuwsbriefen.";
                $message_type = "success";
            } else {
                $message = "Er is een fout opgetreden bij uw inschrijving.";
                $message_type = "error";
            }
            $stmt->close();
        }
        $check_stmt->close();
    } else {
        $message = "Vul alstublieft uw naam en een geldig e-mailadres in.";
        $message_type = "error";
    }
}

$db->close();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Nieuwsbrief Inschrijven - KVW Gorinchem</title>
    <link rel="stylesheet" href="css/styles.css?v=2">
    <style>
        .success { color: #2d5016; background: #c8e6c9; padding: 12px; border-radius: 5px; margin: 15px 0; }
        .error { color: #b71c1c; background: #ffcdd2; padding: 12px; border-radius: 5px; margin: 15px 0; }
    </style>
</head>
<body>

<header>
    <h1>📧 Schrijf in op de Nieuwsbrief</h1>
    <h2>Ontvang alle updates van het Koninklijk Paleis</h2>
</header>

<nav>
    <ul>
        <li><a href="index.php">🏰 Home</a></li>
        <li><a href="program.php">📅 Programma</a></li>
        <li><a href="photos.php">🏛️ Kunstcabinet</a></li>
        <li><a href="news.php">📰 Kronieken</a></li>
    </ul>
</nav>

<main>

<section>
    <h2>Nieuwsbrief Inschrijving</h2>

    <?php if ($message): ?>
        <div class="<?php echo $message_type; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label>Uw naam:</label><br>
        <input type="text" name="name" placeholder="Bv. Jan Jansen" required><br><br>

        <label>Uw e-mailadres:</label><br>
        <input type="email" name="email" placeholder="bv. jan@example.com" required><br><br>

        <input type="submit" value="Inschrijven op Nieuwsbrief">
    </form>

    <hr>

    <h3>Waarom inschrijven?</h3>
    <ul>
        <li>Ontvang alle laatste updates van het Koninklijk Paleis</li>
        <li>Wees als eerste op de hoogte van nieuwe programma's</li>
        <li>Krijg exclusieve informatie over activiteiten</li>
        <li>Handige reminders voor speciale evenementen</li>
    </ul>

</section>

</main>

<footer>
    <p>&copy; 2026 KVW Gorinchem</p>
</footer>

</body>
</html>
