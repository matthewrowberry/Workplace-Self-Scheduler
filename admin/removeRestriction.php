<?php
header('Content-Type: application/json');

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



require '../config.php';

$reservation = $data;

if (!isset($reservation['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing id']);
    exit;
}

$id = $reservation['id'];







try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);



    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $checkSql = $conn->prepare("SELECT COUNT(*) FROM startTimes WHERE id = :id");
    $checkSql->bindParam(':id', $reservation['id']);
    $checkSql->execute();

    if ($checkSql->fetchColumn() == 0) {
        echo json_encode(['message' => 'No restriction exists to delete']);
        exit;
    }


    $sql = $conn->prepare("DELETE FROM startTimes WHERE id = :id");
    $sql->bindParam(':id', $id);


    $sql->execute();
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}






$conn = null;

http_response_code(200);
echo json_encode(['message' => 'Restriction deleted successfully']);
