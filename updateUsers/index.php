<?php
$users = fopen("../../dash/data/users.txt", "r") or die("Unable to open file!");

require '../createUserTable/index.php';
require '../config.php';

if ($users) {

    while (!feof($users)) {
        $line = fgets($users);

        if (trim($line) === '') {
            continue;
        }

        $id = substr($line, 0, strpos($line, "="));
        $name = substr($line, strpos($line, "=") + 1, strlen($line) - strpos($line, "=") - 2);


        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = $conn->prepare("INSERT INTO users (id, name) SELECT :id, :name WHERE NOT EXISTS (SELECT 1 FROM users WHERE id = :id_check)");
            $sql->bindParam(':id', $id);
            $sql->bindParam(':name', $name);
            $sql->bindParam(':id_check', $id);
            $sql->execute();
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
    }
}
