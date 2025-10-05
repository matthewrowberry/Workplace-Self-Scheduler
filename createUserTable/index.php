<?php

require "../config.php";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "CREATE TABLE IF NOT EXISTS users (id VARCHAR(10) PRIMARY KEY, name VARCHAR(50))";
    $conn->exec($sql);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

$conn = null;
