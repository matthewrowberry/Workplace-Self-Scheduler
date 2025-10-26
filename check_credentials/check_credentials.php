<?php
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header('HTTP/1.0 403 Forbidden');


    echo "PHP_SELF: " . $_SERVER['PHP_SELF'];
    echo "FILE: " . __FILE__;
    echo "Basename PHP_SELF: " . basename($_SERVER['PHP_SELF']);
    echo "Basename FILE: " . basename(__FILE__);
    exit('Direct access not allowed');
}

require '../config.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = $conn->prepare("SELECT id, name FROM users WHERE id=:id");
    $sql->bindParam(':id', $id);

    $sql->execute();

    $result = $sql->fetch(PDO::FETCH_ASSOC);

    $id = "";

    if ($result) {
        $id = $result['id'];
        $name = $result['name'];
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}


$conn = null;
