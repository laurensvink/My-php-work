<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$host = "localhost";
$dbname = "projectweek2";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titel = $_POST["titel"];

    $targetDir = "uploads/";
    $fileName = basename($_FILES["image"]["name"]);
    $targetFile = $targetDir . time() . "_" . $fileName;

    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $allowedTypes = ["jpg", "jpeg", "png", "webp"];

    if (!in_array($imageFileType, $allowedTypes)) {
        $_SESSION["message"] = "Slechts Afbeeldingen in JPG, JPEG, PNG & WEBP zijn toegestaan.";
        header("Location: " . $_SERVER["PHP_SELF"]);
        exit;
    }

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
        $stmt = $conn->prepare("INSERT INTO photos (titel, link, user_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $titel, $targetFile, $user_id);

        if ($stmt->execute()) {
            $_SESSION["message"] = "Het kunstwerk is met succes in het Kunstcabinet geplaatst!";
        } else {
            $_SESSION["message"] = "Zware fout in het Register der Kunstwerken.";
        }

        $stmt->close();
    } else {
        $_SESSION["message"] = "Het kunstwerk kon niet worden geplaatst.";
    }

    header("Location: " . $_SERVER["PHP_SELF"]);
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kunstwerk Uploaden - KVW Gorinchem</title>
    <link rel="stylesheet" href="css/styles.css?v=2">
    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            const previewContainer = document.getElementById('previewContainer');
            const previewImage = document.getElementById('previewImage');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewContainer.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                previewContainer.style.display = 'none';
            }
        }
    </script>
</head>
<body>

<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="photos.php">Kunstcabinet Bekijken</a></li>
        <li><a href="admin.php">Paleis</a></li>
        <li><a href="logout.php">Uitloggen</a></li>
    </ul>
</nav>

<h2>Kunstwerk Uploaden</h2>

<?php
if (isset($_SESSION["message"])) {
    echo "<p>" . $_SESSION["message"] . "</p>";
    unset($_SESSION["message"]);
}
?>

<form method="POST" enctype="multipart/form-data">
    <label>Titel van het kunstwerk:</label><br>
    <input type="text" name="titel" required><br><br>

    <label>Selecteer afbeelding:</label><br>
    <input type="file" name="image" id="imageInput" accept="image/*" onchange="previewImage(event)" required><br><br>

    <div id="previewContainer" style="display:none; margin-bottom: 20px; background-color: #3a3a3a; padding: 20px; border: 3px solid #8b5a2b;">
        <label style="color: #d4af37; font-weight: bold;">Voorbeeld:</label><br>
        <img id="previewImage" src="" alt="Preview" style="max-width: 100%; max-height: 400px; border: 2px solid #d4af37; margin-top: 10px;">
    </div>

    <button type="submit">Kunstwerk Inleveren</button>
</form>

<br>
<a href="photos.php">
    <button>Terug naar Kunstcabinet</button>
</a>

</body>
</html>