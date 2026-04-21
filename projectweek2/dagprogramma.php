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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["add"])) {

    $dag = $_POST["dag"];
    $dagdeel = $_POST["dagdeel"];
    $leeftijd = $_POST["leeftijd"];
    $naam = trim($_POST["naam"]);
    $beschrijving = trim($_POST["beschrijving"]);

    if (!empty($dag) && !empty($dagdeel) && !empty($leeftijd) && !empty($naam) && strlen($naam) <= 30 && strlen($beschrijving) <= 200) {

        $query = "INSERT INTO activities (name, description, day, time_slot, age_group, user_id)
                  VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = $db->prepare($query);

        if (!$stmt) {
            die("Prepare fout: " . $db->error);
        }

        $stmt->bind_param("ssissi", $naam, $beschrijving, $dag, $dagdeel, $leeftijd, $user_id);
        $stmt->execute();

        $message = "Activiteit toegevoegd!";
        $stmt->close();

    } else {
        $message = "Vul alle verplichte velden in (naam max 30, beschrijving max 200).";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
    $id = intval($_POST["id"]);
    $dag = $_POST["dag"];
    $dagdeel = $_POST["dagdeel"];
    $leeftijd = $_POST["leeftijd"];
    $naam = trim($_POST["naam"]);
    $beschrijving = trim($_POST["beschrijving"]);

    if (!empty($dag) && !empty($dagdeel) && !empty($leeftijd) && !empty($naam) && strlen($naam) <= 30 && strlen($beschrijving) <= 200) {
        // Check ownership
        $query_check = "SELECT id FROM activities WHERE id = ? AND user_id = ?";
        $stmt_check = $db->prepare($query_check);
        $stmt_check->bind_param("ii", $id, $user_id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows == 1) {
            $query = "UPDATE activities SET name = ?, description = ?, day = ?, time_slot = ?, age_group = ? WHERE id = ?";
            $stmt = $db->prepare($query);
            $stmt->bind_param("ssissi", $naam, $beschrijving, $dag, $dagdeel, $leeftijd, $id);
            $stmt->execute();
            $message = "Activiteit bijgewerkt!";
            $stmt->close();
        } else {
            $message = "Geen toestemming om deze activiteit te wijzigen.";
        }
        $stmt_check->close();
    } else {
        $message = "Vul alle verplichte velden in.";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {
    $id = intval($_POST["id"]);

    // Check if belongs to user
    $query_check = "SELECT id FROM activities WHERE id = ? AND user_id = ?";
    $stmt_check = $db->prepare($query_check);
    $stmt_check->bind_param("ii", $id, $user_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows == 1) {
        $query = "DELETE FROM activities WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $message = "Activiteit verwijderd!";
        $stmt->close();
    } else {
        $message = "Geen toestemming.";
    }
    $stmt_check->close();
}

// Get edit item if requested
$edit_item = null;
if (isset($_GET['edit_id'])) {
    $edit_id = intval($_GET['edit_id']);
    $query_edit = "SELECT * FROM activities WHERE id = ? AND user_id = ?";
    $stmt_edit = $db->prepare($query_edit);
    $stmt_edit->bind_param("ii", $edit_id, $user_id);
    $stmt_edit->execute();
    $result_edit = $stmt_edit->get_result();
    if ($result_edit && $result_edit->num_rows == 1) {
        $edit_item = $result_edit->fetch_assoc();
    }
    $stmt_edit->close();
}

$query = "SELECT * FROM activities ORDER BY day, time_slot, age_group";
$result = $db->query($query);

$activiteiten = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $activiteiten[] = $row;
    }
}

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
    <title>Koninklijke Agenda - KVW Gorinchem</title>
    <link rel="stylesheet" href="css/styles.css?v=2">

    <script>
        function confirmDelete() {
            return confirm("Weet je zeker dat je deze activiteit wilt verwijderen?");
        }
    </script>
</head>
<body>

<header>
    <h1>Koninklijke Agenda Beheren</h1>
</header>

<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="program.php">Dagprogramma</a></li>
        <li><a href="photos.php">Foto's</a></li>
        <li><a href="news.php">Nieuws</a></li>
        <li><a href="admin.php">Beheer</a></li>
        <li><a href="logout.php">Uitloggen</a></li>
    </ul>
</nav>

<main>

<section>
    <h2><?php echo $edit_item ? 'Activiteit Wijzigen' : 'Activiteit toevoegen'; ?></h2>

    <form method="POST">
        <?php if ($edit_item): ?>
            <input type="hidden" name="update" value="1">
            <input type="hidden" name="id" value="<?php echo $edit_item['id']; ?>">
        <?php else: ?>
            <input type="hidden" name="add" value="1">
        <?php endif; ?>

        <label>Dag (1-5):</label><br>
        <input type="number" name="dag" min="1" max="5" value="<?php echo $edit_item ? $edit_item['day'] : ''; ?>" required><br><br>

        <label>Dagdeel:</label><br>
        <select name="dagdeel" required>
            <option value="">Selecteer...</option>
            <option value="ochtend" <?php echo ($edit_item && $edit_item['time_slot'] == 'ochtend') ? 'selected' : ''; ?>>Ochtend</option>
            <option value="middag" <?php echo ($edit_item && $edit_item['time_slot'] == 'middag') ? 'selected' : ''; ?>>Middag</option>
            <option value="avond" <?php echo ($edit_item && $edit_item['time_slot'] == 'avond') ? 'selected' : ''; ?>>Avond</option>
        </select><br><br>

        <label>Leeftijdsgroep:</label><br>
        <select name="leeftijd" required>
            <option value="">Selecteer...</option>
            <option value="10-12" <?php echo ($edit_item && $edit_item['age_group'] == '10-12') ? 'selected' : ''; ?>>Onderbouw (10-12)</option>
            <option value="13-15" <?php echo ($edit_item && $edit_item['age_group'] == '13-15') ? 'selected' : ''; ?>>Middenbouw (13-15)</option>
            <option value="16-18" <?php echo ($edit_item && $edit_item['age_group'] == '16-18') ? 'selected' : ''; ?>>Bovenbouw (16-18)</option>
        </select><br><br>

        <label>Naam (max 30 tekens):</label><br>
        <input type="text" name="naam" maxlength="30" value="<?php echo $edit_item ? htmlspecialchars($edit_item['name']) : ''; ?>" required><br><br>

        <label>Beschrijving (max 200 tekens):</label><br>
        <textarea name="beschrijving" maxlength="200" rows="4" required><?php echo $edit_item ? htmlspecialchars($edit_item['description']) : ''; ?></textarea><br><br>

        <input type="submit" value="<?php echo $edit_item ? 'Bijwerken' : 'Toevoegen'; ?>">
        <?php if ($edit_item): ?>
            <a href="dagprogramma.php"><input type="button" value="Annuleren"></a>
        <?php endif; ?>

    </form>

    <p><?php echo $message; ?></p>
</section>

<hr>

<section>
    <h2>Programma per Leeftijdsgroep</h2>

    <?php
    $days = [1 => 'Dag 1', 2 => 'Dag 2', 3 => 'Dag 3', 4 => 'Dag 4', 5 => 'Dag 5'];
    $time_slots = ['ochtend' => 'Ochtend', 'middag' => 'Middag', 'avond' => 'Avond'];
    $age_groups = [
        '10-12' => 'Onderbouw (10-12 jaar)',
        '13-15' => 'Middenbouw (13-15 jaar)',
        '16-18' => 'Bovenbouw (16-18 jaar)'
    ];

    foreach ($age_groups as $age_code => $age_name) {
        echo "<h3>📅 " . $age_name . "</h3>";
        $group_activities = array_filter($activiteiten, function($a) use ($age_code) { return $a['age_group'] == $age_code; });

        if (empty($group_activities)) {
            echo "<p>Geen activiteiten voor deze groep.</p>";
            continue;
        }

        foreach ($days as $day_num => $day_name) {
            echo "<h4>" . $day_name . "</h4>";
            $day_activities = array_filter($group_activities, function($a) use ($day_num) { return $a['day'] == $day_num; });

            if (empty($day_activities)) {
                echo "<p><em>Geen activiteiten.</em></p>";
                continue;
            }

            foreach ($time_slots as $slot => $slot_name) {
                $slot_activities = array_filter($day_activities, function($a) use ($slot) { return $a['time_slot'] == $slot; });

                if (!empty($slot_activities)) {
                    echo "<strong>" . $slot_name . ":</strong><br>";
                    foreach ($slot_activities as $act) {
                        echo "<div style='margin: 10px 0; padding: 10px; background: rgba(255,255,255,0.5); border-radius: 5px;'>";
                        echo "<strong>" . htmlspecialchars($act['name']) . ":</strong> " . htmlspecialchars($act['description']);

                        if (isset($user_id) && isset($act['user_id']) && $act['user_id'] == $user_id) {
                            echo " <a href='dagprogramma.php?edit_id=" . $act['id'] . "'><button>✏️ Wijzigen</button></a>";
                            echo " <form method='POST' onsubmit='return confirmDelete();' style='display:inline;'>";
                            echo "<input type='hidden' name='delete' value='1'>";
                            echo "<input type='hidden' name='id' value='" . $act['id'] . "'>";
                            echo "<input type='submit' value='🗑️ Verwijderen'>";
                            echo "</form>";
                        }
                        echo "</div>";
                    }
                }
            }
        }
        echo "<hr>";
    }
    ?>
</section>

</main>

<footer>
    <p>&copy; 2026 KVW Gorinchem</p>
</footer>

</body>
</html>