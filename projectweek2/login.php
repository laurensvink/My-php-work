<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: admin.php");
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $host = "localhost";
    $dbname = "projectweek2";
    $user = "root";
    $pass = "";

    $conn = new mysqli($host, $user, $pass, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $username;
            header("Location: admin.php");
            exit;
        } else {
            $message = "Verkeerde sleutel tot het kasteel!";
        }
    } else {
        $message = "Gij zijt niet in het register van de burcht.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Toegang tot de Burcht - KVW Gorinchem</title>    <link rel="stylesheet" href="css/styles.css?v=2"></head>
<body>

<h1>Toegang tot de Burcht</h1>

<form method="POST">
    <label>Je naam:</label><br>
    <input type="text" name="username" required><br><br>

    <label>Het geheim:</label><br>
    <input type="password" name="password" required><br><br>

    <input type="submit" value="Toegang Verlenen">
</form>

<p><?php echo $message; ?></p>

</body>
</html>