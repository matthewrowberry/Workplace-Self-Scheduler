<?php

date_default_timezone_set('America/Denver');

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

$now = new DateTime(); // Current date and time
$sqlDateTime = $now->format('Y-m-d H:i:s'); // 2025-10-28 14:30:45

$results = [];

foreach ($data as $index => $reservation) {
    $result = ['index' => $index];

    if (!isset($reservation['id'], $reservation['name'], $reservation['date'], $reservation['start'], $reservation['end'], $reservation['station'])) {
        $result['error'] = 'Malformed Removal Request (Error 808)';
        $results[] = $result;
        continue;
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
        $startStr = $start->format('H:i:s');
        $endStr = $end->format('H:i:s');
    } catch (Exception $e) {
        $result['error'] = 'Malformed Removal Request (Error 809)';
        $results[] = $result;
        continue;
    }




    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = $conn->prepare("DELETE FROM reservations WHERE code = :code AND name = :name AND date = :date AND start = :start AND end = :end AND station = :station");
        $sql->bindParam(':code', $reservation['id']);
        $sql->bindParam(':name', $reservation['name']);
        $sql->bindParam(':date', $reservation['date']);
        $sql->bindParam(':start', $startStr);
        $sql->bindParam(':end', $endStr);
        $sql->bindParam(':station', $reservation['station']);
        $sql->execute();

        $sql = $conn->prepare("INSERT INTO deletedReservations (code, name, date, start, end, station, changed) VALUES (:code, :name, :date, :start, :end, :station, :changed)");
        $sql->bindParam(':code', $reservation['id']);
        $sql->bindParam(':name', $reservation['name']);
        $sql->bindParam(':date', $reservation['date']);
        $sql->bindParam(':start', $startStr);
        $sql->bindParam(':end', $endStr);
        $sql->bindParam(':station', $reservation['station']);
        $sql->bindParam(':changed', $sqlDateTime);

        $sql->execute();

        $result['success'] = true;
        $result['message'] = 'Reservation removed successfully';
        $results[] = $result;
    } catch (PDOException $e) {
        $result['error'] = "Database error";
        $results[] = $result;
        
    }
}





$conn = null;

http_response_code(200);
echo json_encode(['results' => $results]);

exit;