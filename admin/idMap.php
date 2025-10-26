<?php

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = $conn->prepare("SELECT id, name FROM users");
    $sql->execute();

    $results = $sql->fetchAll(PDO::FETCH_ASSOC);


    return $results;
} catch (PDOException $e) {

    echo "Connection failed: " . $e->getMessage();
}



$conn = null; ?>;

?>