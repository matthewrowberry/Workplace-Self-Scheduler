<?php

require '../config.php';




$sat = date('Y-m-d', strtotime('last saturday'));
$sun = date('Y-m-d', strtotime('next sunday'));



//PUT ID VERIFICATION HERE

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = $conn->prepare("SELECT name, date, start, end, station FROM reservations WHERE date>:sat AND date<:sun");
    $sql->bindParam(':sat', $sat);
    $sql->bindParam(':sun', $sun);



    $sql->execute();

    $results = $sql->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($results);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}



$conn = null;
