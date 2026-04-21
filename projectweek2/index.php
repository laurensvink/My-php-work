<?php

$dbhost = "127.0.0.1";
$dbuser = "root";
$dbpass = "";
$dbname = "projectweek2";

$dblink = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)
          or die("Niet mogelijk om te verbinden");

$query = "SELECT titel, news FROM news ORDER BY id DESC LIMIT 1";
$result = mysqli_query($dblink, $query);
$latest_news = mysqli_fetch_assoc($result);

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");

    if (!empty($name) && !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Check if email already exists
        $check_query = "SELECT id FROM newsletter_subscribers WHERE email = ?";
        $check_stmt = $dblink->prepare($check_query);
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $message = "Dit e-mailadres is al ingeschreven op de Koninklijke Nieuwsbrief!";
            $message_type = "error";
        } else {
            $query_insert = "INSERT INTO newsletter_subscribers (name, email) VALUES (?, ?)";
            $stmt = $dblink->prepare($query_insert);
            $stmt->bind_param("ss", $name, $email);
            
            if ($stmt->execute()) {
                $message = "Dank u voor uw inschrijving! De Koninklijke Nieuwsbrief zal u regelmatig bereiken.";
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

mysqli_close($dblink);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>KVW Gorinchem 2026 - Middeleeuwen</title>
    <link rel="stylesheet" href="css/styles.css?v=2">
    <style>
        .success { color: #2d5016; background: #c8e6c9; padding: 12px; border-radius: 5px; margin: 15px 0; border: 2px solid #66bb6a; }
        .error { color: #b71c1c; background: #ffcdd2; padding: 12px; border-radius: 5px; margin: 15px 0; border: 2px solid #ef5350; }
    </style>
</head>
<body>

<header>
    <h1>Welkom bij KVW Gorinchem 2026!</h1>
    <h2>Thema: Middeleeuwen</h2>
</header>

<nav>
    <ul>
        <li><a href="#">Home</a></li>
        <li><a href="program.php">Dagprogramma</a></li>
        <li><a href="photos.php">Foto's & Video's</a></li>
        <li><a href="news.php">Nieuws</a></li>
        <li><a href="login.php">Begeleiders Login</a></li>
    </ul>
</nav>

<main>

<section>
    <h3>Welkom in het Koninkrijk!</h3>
    <p>
        Van 20 t/m 24 juli gaan we terug naar de Middeleeuwen!
        Beleef avonturen met ridders, kastelen en spannende spellen.
    </p>
    <p><em>Bereidt je goed voor op dit legendaire avontuur, edele ridder!</em></p>
    <img src="assets/swordfight.gif" alt="Swordfight animation">
</section>

<section>
    <h3>Koninklijk Decreet</h3>
    <?php if ($latest_news): ?>
        <h4><?php echo htmlspecialchars($latest_news['titel']); ?></h4>
        <p><?php echo htmlspecialchars($latest_news['news']); ?></p>
    <?php else: ?>
        <p>Geen nieuws beschikbaar.</p>
    <?php endif; ?>
</section>

<section>
    <h3>📧 Schrijf in op de Koninklijke Nieuwsbrief</h3>
    
    <?php if ($message): ?>
        <div class="<?php echo $message_type; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <p>Ontvang alle updates en aankondigingen van het Koninklijke Paleis rechtstreeks in uw postbus!</p>

    <form method="POST">
        <label>Uw naam:</label><br>
        <input type="text" name="name" placeholder="Bv. Jan Jansen" required><br><br>

        <label>Uw e-mailadres:</label><br>
        <input type="email" name="email" placeholder="bv. jan@example.com" required><br><br>

        <input type="submit" value="Inschrijven op Nieuwsbrief">
    </form>
</section>

</main>

<footer>
    <p>&copy; 2026 KVW Gorinchem</p>
</footer>

</body>
</html>