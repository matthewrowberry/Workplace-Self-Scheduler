<?php


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

foreach ($data as $reservation) {

    if (!isset($reservation['id'], $reservation['name'], $reservation['date'], $reservation['start'], $reservation['end'], $reservation['station'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Missing required fields in reservation']);
        exit;
    }

    $id = intval($reservation['id']);
    $name = trim($reservation['name']);
    try {
        $date = new DateTime($reservation['date']);
        $start = DateTime::createFromFormat('H:i:s', $reservation['start']);
        $end = DateTime::createFromFormat('H:i:s', $reservation['end']);
        if (!$start || !$end) {
            throw new Exception('Invalid time format');
        }
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid date/time: ' . $e->getMessage()]);
        exit;
    }




    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = $conn->prepare("DELETE FROM reservations WHERE code = :code AND name = :name AND date = :date AND start = :start AND end = :end AND station = :station");
        $sql->bindParam(':code', $reservation['id']);
        $sql->bindParam(':name', $reservation['name']);
        $sql->bindParam(':date', $reservation['date']);
        $sql->bindParam(':start', $reservation['start']);
        $sql->bindParam(':end', $reservation['end']);
        $sql->bindParam(':station', $reservation['station']);



        $sql->execute();
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }
}





$conn = null;

http_response_code(200);
echo json_encode(['message' => 'Reservations deleted successfully']);
