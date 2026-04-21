<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    $host = "localhost";
    $dbname = "projectweek2";
    $user = "root";
    $pass = "";

    $conn = new mysqli($host, $user, $pass, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT link FROM photos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $link = $row['link'];

        $stmt2 = $conn->prepare("DELETE FROM photos WHERE id = ?");
        $stmt2->bind_param("i", $id);
        $stmt2->execute();
        $stmt2->close();

        if (file_exists(__DIR__ . '/' . $link)) {
            unlink(__DIR__ . '/' . $link);
        }

        $_SESSION["message"] = "Foto verwijderd!";
    } else {
        $_SESSION["message"] = "Foto niet gevonden.";
    }

    $stmt->close();
    $conn->close();
}

header("Location: photos.php");
exit;
?>