<?php
$host = "localhost";
$dbname = "projectweek2";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "<h2>Testing Database Connection</h2>";
echo "✓ Connected to database 'projectweek2'<br><br>";

// Check users table
$result = $conn->query("SELECT * FROM users");
echo "<h3>Users in Database:</h3>";
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "Username: " . htmlspecialchars($row['username']) . "<br>";
        echo "Password Hash: " . substr($row['password'], 0, 20) . "...<br>";
        echo "ID: " . $row['id'] . "<br><br>";
    }
} else {
    echo "❌ NO USERS FOUND IN DATABASE<br>";
}

// Check all tables
echo "<h3>All Tables in Database:</h3>";
$tables = $conn->query("SHOW TABLES");
if ($tables && $tables->num_rows > 0) {
    while ($table = $tables->fetch_array()) {
        echo "- " . $table[0] . "<br>";
    }
} else {
    echo "No tables found<br>";
}

$conn->close();
?>
