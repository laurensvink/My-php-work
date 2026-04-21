<?php
session_start();

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

$host = "localhost";
$dbname = "projectweek2";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if (isset($user_id) && $_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete"])) {
    $id = intval($_POST["id"]);

    $query_check = "SELECT id FROM activities WHERE id = ? AND user_id = ?";
    $stmt_check = $conn->prepare($query_check);

    if ($stmt_check) {
        $stmt_check->bind_param("ii", $id, $user_id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows == 1) {
            $query = "DELETE FROM activities WHERE id = ?";
            $stmt = $conn->prepare($query);

            if(!$stmt){
                die("Prepare failed: " . $conn->error);
            }

            $stmt->bind_param("i", $id);
            $stmt->execute();

            $message = "Activiteit verwijderd!";
            $stmt->close();
        } else {
            $message = "Geen toestemming om deze activiteit te verwijderen.";
        }

        $stmt_check->close();
    }
}

$result = $conn->query("SELECT * FROM activities ORDER BY day, time_slot, age_group");

$activities = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $activities[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Koninklijk Dagprogramma - KVW Gorinchem</title>
    <link rel="stylesheet" href="css/styles.css?v=2">
</head>
<body>

<header>
    <h1>Het Koninklijke Dagprogramma</h1>
    <h2>Thema: Middeleeuwen</h2>
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

<?php if (isset($message)): ?>
    <div class="message"><?php echo htmlspecialchars($message); ?></div>
<?php endif; ?>

<?php if (isset($user_id)): ?>
    <a class="upload-btn" href="dagprogramma.php">Activiteiten beheren</a>
<?php endif; ?>

    <h2>Activiteiten per dag</h2>

    <?php
    $days = [1 => 'Dag 1', 2 => 'Dag 2', 3 => 'Dag 3', 4 => 'Dag 4', 5 => 'Dag 5'];
    $time_slots = ['ochtend' => 'Ochtend', 'middag' => 'Middag', 'avond' => 'Avond'];
    $age_groups = ['10-12', '13-15', '16-18'];

    foreach ($days as $day_num => $day_name) {
        echo "<h3>$day_name</h3>";
        $day_activities = array_filter($activities, function($a) use ($day_num) { return $a['day'] == $day_num; });

        if (empty($day_activities)) {
            echo "<p>Geen activiteiten gepland.</p>";
            continue;
        }

        foreach ($time_slots as $slot => $slot_name) {
            $slot_activities = array_filter($day_activities, function($a) use ($slot) { return $a['time_slot'] == $slot; });

            if (!empty($slot_activities)) {
                echo "<h4>$slot_name</h4>";
                foreach ($age_groups as $age) {
                    $age_activities = array_filter($slot_activities, function($a) use ($age) { return $a['age_group'] == $age; });

                    if (!empty($age_activities)) {
                        echo "<h5>Leeftijd $age</h5>";
                        echo "<ul>";
                        foreach ($age_activities as $act) {
                            echo "<li><strong>" . htmlspecialchars($act['name']) . "</strong>: " . htmlspecialchars($act['description']);
                            if (isset($user_id) && $act['user_id'] == $user_id) {
                                echo " <form method='POST' style='display:inline;' onsubmit='return confirm(\"Weet je zeker dat je deze activiteit wilt verwijderen?\")'>";
                                echo "<input type='hidden' name='delete' value='1'>";
                                echo "<input type='hidden' name='id' value='" . $act['id'] . "'>";
                                echo "<button type='submit' class='delete-btn' style='font-size: 0.8em; padding: 2px 6px;'>Verwijderen</button>";
                                echo "</form>";
                            }
                            echo "</li>";
                        }
                        echo "</ul>";
                    }
                }
            }
        }
    }
    ?>
</main>

<footer>
    <p>&copy; 2026 KVW Gorinchem</p>
</footer>

</body>
</html>