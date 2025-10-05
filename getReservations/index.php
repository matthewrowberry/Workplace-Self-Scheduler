<?php

require '../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Only POST requests are allowed']);
    exit;
}

$input = file_get_contents('php://input');

$data = json_decode($input, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON: ' . json_last_error_msg()]);
    exit;
}

if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(['error' => 'Data must be a JSON array']);
    exit;
}


if (!isset($data['id'], $data['sat'], $data['sun'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}


$sat = trim($data['sat']);
$sun = trim($data['sun']);
$id = intval($data['id']);


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
