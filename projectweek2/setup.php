<?php
$host = "localhost";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->query("CREATE DATABASE IF NOT EXISTS projectweek2");

$conn->select_db("projectweek2");

$conn->query("CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
)");

$conn->query("CREATE TABLE IF NOT EXISTS news (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titel VARCHAR(100) NOT NULL,
    news VARCHAR(128) NOT NULL,
    datum TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id)
)");

$conn->query("CREATE TABLE IF NOT EXISTS photos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titel VARCHAR(255) NOT NULL,
    link VARCHAR(255) NOT NULL,
    datum TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id)
)");

$conn->query("CREATE TABLE IF NOT EXISTS activities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30) NOT NULL,
    description VARCHAR(200) NOT NULL,
    day INT NOT NULL,
    time_slot ENUM('ochtend', 'middag', 'avond') NOT NULL,
    age_group ENUM('10-12', '13-15', '16-18') NOT NULL,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id)
)");

$conn->query("CREATE TABLE IF NOT EXISTS newsletter_subscribers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) UNIQUE NOT NULL,
    name VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$password_hash = password_hash("admin", PASSWORD_DEFAULT);
$username = "admin";
$stmt = $conn->prepare("INSERT IGNORE INTO users (username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $password_hash);
$stmt->execute();
$stmt->close();

$conn->close();

echo "Database setup complete. Default user: admin / admin";
?>