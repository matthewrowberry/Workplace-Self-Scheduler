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
$required = ['id', 'startFromWeekday', 'prevDaysOffset', 'hour', 'minute'];
$missing  = [];

foreach ($required as $field) {
    if (!isset($reservation[$field])) {
        $missing[] = $field;
    }
}

if ($missing) {
    http_response_code(400);
    echo json_encode([
        'error'   => 'Missing required fields in reservation',
        'missing' => $missing
    ]);
    exit;
}

if (!isset($reservation['id'], $reservation['startFromWeekday'], $reservation['prevDaysOffset'], $reservation['hour'], $reservation['minute'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required fields in reservation']);
    exit;
}

$id = $reservation['id'];
$weekdayStart = $reservation['startFromWeekday'];
$offsetPrev = $reservation['prevDaysOffset'];
$hour = $reservation['hour'];
$minute = $reservation['minute'];






try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);



    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $checkSql = $conn->prepare("SELECT COUNT(*) FROM startTimes WHERE id = :id");
    $checkSql->bindParam(':id', $reservation['id']);
    $checkSql->execute();

    if ($checkSql->fetchColumn() > 0) {
        echo json_encode(['message' => 'A restriction already exists for this user']);
        exit;
    }


    $sql = $conn->prepare("INSERT INTO startTimes (id, startFromWeekday, prevDaysOffset, hour, minute) VALUES (:id, :startFromWeekday, :prevDaysOffset, :hour, :minute)");
    $sql->bindParam(':id', $id);
    $sql->bindParam(':startFromWeekday', $weekdayStart);
    $sql->bindParam(':prevDaysOffset', $offsetPrev);
    $sql->bindParam(':hour', $hour);
    $sql->bindParam(':minute', $minute);

    $sql->execute();
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}






$conn = null;

http_response_code(200);
echo json_encode(['message' => 'Reservations added successfully']);
