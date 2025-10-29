<?php
require "../datelock/index.php";
require "../config.php";
function check($id, $name, $date, $start, $end, $station)
{
    global $servername, $dbname, $username, $password;
    $sunday = new DateTime('next Sunday');
    $reservationDate = new DateTime($date);
    if ($sunday < $reservationDate) {

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = $conn->prepare("SELECT id, startFromWeekday, prevDaysOffset, hour, minute FROM startTimes WHERE id = :id");
            $sql->bindParam(':id', $id);
            $sql->execute();

            $row = $sql->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                if (!checkAfter($row['startFromWeekday'], $row['prevDaysOffset'], $row['hour'], $row['minute'])) {
                    return 1;
                }
            } else {
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = $conn->prepare("SELECT id, startFromWeekday, prevDaysOffset, hour, minute FROM startTimes WHERE id = 0");
                $sql->execute();

                $row = $sql->fetch(PDO::FETCH_ASSOC);

                if ($row) {
                    if (!checkAfter($row['startFromWeekday'], $row['prevDaysOffset'], $row['hour'], $row['minute'])) {
                        return 1;
                    }
                }
            }
        } catch (PDOException $e) {

            echo "Connection failed: " . $e->getMessage();
        }



        $conn = null;
    }
    return 0;
}
