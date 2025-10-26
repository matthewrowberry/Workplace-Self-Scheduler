<?php 

require '../config.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = $conn->prepare("SELECT name, date, start, end FROM reservations WHERE date>:sat AND date<:sun");
    $sql->bindParam(':sat', $reservation['id']);
    $sql->bindParam(':name', $reservation['name']);
    $sql->bindParam(':date', $reservation['date']);
    $sql->bindParam(':start', $reservation['start']);
    $sql->bindParam(':end', $reservation['end']);
    
    $sql->execute();

} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
    


$conn = null;

?>