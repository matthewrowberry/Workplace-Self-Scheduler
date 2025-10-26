<?php
require '../config.php';
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "CREATE TABLE IF NOT EXISTS deletedReservations (id INT AUTO_INCREMENT PRIMARY KEY, code INT, name VARCHAR(50), date DATE, start TIME, end TIME)";
    $conn->exec($sql);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

$conn = null;
