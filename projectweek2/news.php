<?php
session_start();

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

$dbhost = "127.0.0.1";
$dbuser = "root";
$dbpass = "";
$dbname = "projectweek2";

$dblink = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)
          or die("Niet mogelijk om te verbinden");

function ensureNewsUserIdColumn($dblink) {
    $check = $dblink->query("SHOW COLUMNS FROM news LIKE 'user_id'");
    if ($check && $check->num_rows === 0) {
        $dblink->query("ALTER TABLE news ADD COLUMN user_id INT DEFAULT NULL");
        if ($dblink->errno === 0) {
            $dblink->query("ALTER TABLE news ADD CONSTRAINT fk_news_user FOREIGN KEY (user_id) REFERENCES users(id)");
        }
    }
}

ensureNewsUserIdColumn($dblink);

$message = "";
$edit_item = null;

if (isset($user_id) && isset($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $stmt = $dblink->prepare("SELECT id, titel, news FROM news WHERE id = ? AND user_id = ?");

    if ($stmt) {
        $stmt->bind_param("ii", $edit_id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $edit_item = $result->fetch_assoc();
        }

        $stmt->close();
    }
}

if (isset($user_id) && $_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["add"])) {
        $titel = trim($_POST["titel"]);
        $news = trim($_POST["news"]);

        if (!empty($titel) && !empty($news) && strlen($titel) <= 100 && strlen($news) <= 128) {
            $query = "INSERT INTO news (titel, news, user_id) VALUES (?, ?, ?)";
            $stmt = $dblink->prepare($query);

            if(!$stmt){
                die("Prepare niet gelukt: " . $dblink->error);
            }

            $stmt->bind_param("ssi", $titel, $news, $user_id);
            $stmt->execute();

            $message = "Nieuws toegevoegd!";
            $stmt->close();
        } else {
            $message = "Controleer invoer (titel max 100, bericht max 128 tekens)";
        }
    } elseif (isset($_POST["update"])) {
        $id = intval($_POST["id"]);
        $titel = trim($_POST["titel"]);
        $news = trim($_POST["news"]);

        if (!empty($titel) && !empty($news) && strlen($titel) <= 100 && strlen($news) <= 128) {
            $query_check = "SELECT id FROM news WHERE id = ? AND user_id = ?";
            $stmt_check = $dblink->prepare($query_check);

            if ($stmt_check) {
                $stmt_check->bind_param("ii", $id, $user_id);
                $stmt_check->execute();
                $result_check = $stmt_check->get_result();

                if ($result_check->num_rows == 1) {
                    $query = "UPDATE news SET titel = ?, news = ?, datum = CURRENT_TIMESTAMP WHERE id = ?";
                    $stmt = $dblink->prepare($query);

                    if(!$stmt){
                        die("Prepare niet gelukt: " . $dblink->error);
                    }

                    $stmt->bind_param("ssi", $titel, $news, $id);
                    $stmt->execute();

                    $message = "Nieuws bijgewerkt!";
                    $stmt->close();
                    $edit_item = null;
                } else {
                    $message = "Geen toestemming om dit nieuws te wijzigen.";
                }

                $stmt_check->close();
            }
        } else {
            $message = "Controleer invoer (titel max 100, bericht max 128 tekens)";
        }
    } elseif (isset($_POST["delete"])) {
        $id = intval($_POST["id"]);

        $query_check = "SELECT id FROM news WHERE id = ? AND user_id = ?";
        $stmt_check = $dblink->prepare($query_check);

        if ($stmt_check) {
            $stmt_check->bind_param("ii", $id, $user_id);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();

            if ($result_check->num_rows == 1) {
                $query = "DELETE FROM news WHERE id = ?";
                $stmt = $dblink->prepare($query);

                if(!$stmt){
                    die("Prepare niet gelukt: " . $dblink->error);
                }

                $stmt->bind_param("i", $id);
                $stmt->execute();

                $message = "Nieuws verwijderd!";
                $stmt->close();
            } else {
                $message = "Geen toestemming om dit nieuws te verwijderen.";
            }

            $stmt_check->close();
        }
    }
}

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
    <title>Koninklijke Kronieken - KVW Gorinchem</title>
    <link rel="stylesheet" href="css/styles.css?v=2">

    <script>
        function confirmDelete() {
            return confirm("Weet je zeker dat je dit bericht wilt verwijderen?");
        }
    </script>
</head>
<body>

<header>
    <h1>Nieuws & Updates</h1>
</header>

<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="program.php">Dagprogramma</a></li>
        <li><a href="photos.php">Foto's & Video's</a></li>
        <li><a href="news.php">Nieuws</a></li>
        <?php if (isset($user_id)): ?>
            <li><a href="admin.php">Beheer</a></li>
            <li><a href="logout.php">Uitloggen</a></li>
        <?php else: ?>
            <li><a href="login.php">Begeleiders Login</a></li>
        <?php endif; ?>
    </ul>
</nav>

<main>

<?php if (isset($user_id)): ?>
<section>
    <h2>Nieuw Decreet Uitroepen</h2>
    <form method="POST">
        <input type="hidden" name="add" value="1">
        <input type="text" name="titel" placeholder="Titel van het decreet" maxlength="100" required><br><br>
        <input type="text" name="news" placeholder="De koninklijke mededeling" maxlength="128" required><br><br>
        <input type="submit" value="Afkondigen">
    </form>
</section>

<?php if ($edit_item): ?>
<section>
    <h2>Nieuwsbericht Bewerken</h2>
    <form method="POST">
        <input type="hidden" name="update" value="1">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($edit_item['id']); ?>">
        <input type="text" name="titel" value="<?php echo htmlspecialchars($edit_item['titel']); ?>" maxlength="100" required><br><br>
        <input type="text" name="news" value="<?php echo htmlspecialchars($edit_item['news']); ?>" maxlength="128" required><br><br>
        <input type="submit" value="Aanpassen">
    </form>
</section>
<?php endif; ?>

<section>
    <p><?php echo $message; ?></p>
</section>
<?php endif; ?>

<section>
    <h2>Alle Koninklijke Kronieken</h2>

    <?php if (count($nieuwsLijst) > 0): ?>
        <ul>
            <?php foreach ($nieuwsLijst as $item): ?>
                <li>
                    <strong><?php echo htmlspecialchars($item['titel']); ?></strong><br>
                    <?php echo htmlspecialchars($item['news']); ?><br>
                    <small><?php echo $item['datum']; ?></small><br><br>

                    <!-- DELETE BUTTON only if logged in and user's -->
                    <?php if (isset($user_id)): ?>
                        <a class="btn" href="news.php?edit_id=<?php echo $item['id']; ?>">Bewerken</a>
                        <form method="POST" onsubmit="return confirmDelete();" style="display:inline; margin-left: 1rem;">
                            <input type="hidden" name="delete" value="1">
                            <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                            <input type="submit" value="Verwijderen">
                        </form>
                    <?php endif; ?>
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