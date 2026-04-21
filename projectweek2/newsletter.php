<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$db = mysqli_connect("127.0.0.1", "root", "", "projectweek2")
      or die("Database verbinding mislukt");

$message = "";

// Delete subscriber
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {
    $id = intval($_POST["id"]);
    $query = "DELETE FROM newsletter_subscribers WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $message = "Abonnee verwijderd!";
    }
    $stmt->close();
}

// Get all subscribers
$query = "SELECT id, name, email, created_at FROM newsletter_subscribers ORDER BY created_at DESC";
$result = $db->query($query);
$subscribers = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $subscribers[] = $row;
    }
}

$db->close();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Nieuwsbrief Abonnees - KVW Gorinchem</title>
    <link rel="stylesheet" href="css/styles.css?v=2">
    <script>
        function confirmDelete() {
            return confirm("Weet je zeker dat je deze abonnee wilt verwijderen?");
        }
    </script>
</head>
<body>

<header>
    <h1>📜 Nieuwsbrief Abonnees Beheren</h1>
</header>

<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="admin.php">Paleis</a></li>
        <li><a href="news.php">Kronieken</a></li>
        <li><a href="logout.php">Uitloggen</a></li>
    </ul>
</nav>

<main>

<section>
    <h2>Abonnees Overzicht</h2>
    
    <?php if ($message): ?>
        <p style="color: green;"><strong><?php echo htmlspecialchars($message); ?></strong></p>
    <?php endif; ?>

    <p>Totaal aantal abonnees: <strong><?php echo count($subscribers); ?></strong></p>

    <?php if (count($subscribers) > 0): ?>
        <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
            <thead>
                <tr style="background: #7b5b34; color: #fff5d8;">
                    <th style="padding: 12px; text-align: left; border: 1px solid #b08c54;">Naam</th>
                    <th style="padding: 12px; text-align: left; border: 1px solid #b08c54;">E-mailadres</th>
                    <th style="padding: 12px; text-align: left; border: 1px solid #b08c54;">Inschrijfdatum</th>
                    <th style="padding: 12px; text-align: center; border: 1px solid #b08c54;">Actie</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($subscribers as $sub): ?>
                    <tr style="background: rgba(255,250,235,0.8); border-bottom: 1px solid #b08c54;">
                        <td style="padding: 12px; border: 1px solid #b08c54;"><?php echo htmlspecialchars($sub['name']); ?></td>
                        <td style="padding: 12px; border: 1px solid #b08c54;"><?php echo htmlspecialchars($sub['email']); ?></td>
                        <td style="padding: 12px; border: 1px solid #b08c54;"><?php echo date('d-m-Y H:i', strtotime($sub['created_at'])); ?></td>
                        <td style="padding: 12px; border: 1px solid #b08c54; text-align: center;">
                            <form method="POST" onsubmit="return confirmDelete();" style="display:inline;">
                                <input type="hidden" name="delete" value="1">
                                <input type="hidden" name="id" value="<?php echo $sub['id']; ?>">
                                <input type="submit" value="🗑️ Verwijderen" style="padding: 5px 10px; cursor: pointer;">
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div style="margin-top: 20px; padding: 15px; background: rgba(255,250,235,0.9); border-radius: 8px;">
            <h3>📧 E-mail Lijsten</h3>
            <p><strong>Alle abonnees:</strong></p>
            <textarea readonly rows="8" style="width: 100%; font-family: monospace; padding: 10px; border: 1px solid #b08c54;"><?php 
                echo implode(", ", array_map(function($s) { return $s['email']; }, $subscribers));
            ?></textarea>
        </div>

    <?php else: ?>
        <p style="color: #8b5f2e;"><em>Nog geen abonnees.</em></p>
    <?php endif; ?>

</section>

</main>

<footer>
    <p>&copy; 2026 KVW Gorinchem</p>
</footer>

</body>
</html>
