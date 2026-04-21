<?php
$host = "localhost";
$dbname = "projectweek2";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create a fresh bcrypt hash for "admin"
$password_hash = password_hash("admin", PASSWORD_DEFAULT);

echo "New password hash: " . $password_hash . "<br>";

// Delete existing admin user
$conn->query("DELETE FROM users WHERE username = 'admin'");
echo "Deleted old admin user<br>";

// Insert new admin user
$stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("ss", $username, $password_hash);
$username = "admin";

if ($stmt->execute()) {
    echo "✓ Admin user created successfully!<br>";
    echo "Username: admin<br>";
    echo "Password: admin<br>";
} else {
    echo "❌ Error: " . $stmt->error;
}

$stmt->close();

// Verify it was created
$check = $conn->query("SELECT id, username, password FROM users WHERE username = 'admin'");
if ($check && $check->num_rows > 0) {
    $row = $check->fetch_assoc();
    echo "<br>✓ Verified in database:<br>";
    echo "ID: " . $row['id'] . "<br>";
    echo "Username: " . $row['username'] . "<br>";
    echo "Hash: " . substr($row['password'], 0, 30) . "...<br>";
}

$conn->close();
?>
