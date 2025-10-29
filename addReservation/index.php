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
require 'check.php';

$results = [];

foreach ($data as $index => $reservation) {
    $result = ['index' => $index];

    if (!isset($reservation['id'], $reservation['name'], $reservation['date'], $reservation['start'], $reservation['end'], $reservation['station'])) {
        $result['error'] = 'Malformed Submission (Error 808)';
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
        $result['error'] = 'Malformed Submission (Error 809)';
        $results[] = $result;
        continue;
    }

    $checkResult = check($reservation['id'], $reservation['name'], $reservation['date'], $reservation['start'], $reservation['end'], $reservation['station']);
    if ($checkResult != 0) {

        if ($checkResult == 1) {
            $result['error'] = 'You cannot sign up for this slot yet';
        } else {
            $result['error'] = 'Checks Failed';
        }
        $results[] = $result;
        continue;
    }




    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $checkSql = $conn->prepare("SELECT COUNT(*) FROM reservations WHERE station = :station AND date = :date AND ((start <= :start AND end > :start) OR (start < :end AND end >= :end) OR (start >= :start AND end <= :end))");
        $checkSql->bindParam(':date', $reservation['date']);
        $checkSql->bindParam(':start', $startStr);
        $checkSql->bindParam(':end', $endStr);
        $checkSql->bindParam(':station', $reservation['station']);
        $checkSql->execute();

        if ($checkSql->fetchColumn() > 0) {
            $resultl['error'] = 'Slot already reserved';
            $results[] = $result;
            continue;
        }


        $sql = $conn->prepare("INSERT INTO reservations (code, name, date, start, end, station) VALUES (:code, :name, :date, :start, :end, :station)");
        $sql->bindParam(':code', $reservation['id']);
        $sql->bindParam(':name', $reservation['name']);
        $sql->bindParam(':date', $reservation['date']);
        $sql->bindParam(':start', $startStr);
        $sql->bindParam(':end', $endStr);
        $sql->bindParam(':station', $reservation['station']);

        $sql->execute();

        $result['success'] = true;
        $result['message'] = 'Reservation added successfully';
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
