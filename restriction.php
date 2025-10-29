<?php


function getWeekdayFromNum($num)
{
    $sunday = strtotime('next Sunday');
    $timestamp = strtotime("$num days", $sunday);
    return date('l', $timestamp);
}

require "config.php";

function getScheduleTime($id)
{
    global $servername, $dbname, $username, $password;

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = $conn->prepare("SELECT id, startFromWeekday, prevDaysOffset, hour, minute FROM startTimes WHERE id = :id");
        $sql->bindParam(':id', $id);
        $sql->execute();

        $row = $sql->fetch(PDO::FETCH_ASSOC);

        if ($row) {

            $minute = str_pad($row['minute'], 2, '0', STR_PAD_LEFT);
            $output = "You can schedule for the next week after " . (string)$row['hour'] . ":" . $minute . " on the " . getWeekdayFromNum($row['startFromWeekday'] - $row['prevDaysOffset']) . " before the upcoming week.";



            return $output;
        } else {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = $conn->prepare("SELECT id, startFromWeekday, prevDaysOffset, hour, minute FROM startTimes WHERE id = 0");
            $sql->execute();

            $row = $sql->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                $minute = str_pad($row['minute'], 2, '0', STR_PAD_LEFT);
                $output = "You can schedule for the next week after " . (string)$row['hour'] . ":" . $minute . " on the " . getWeekdayFromNum($row['startFromWeekday'] - $row['prevDaysOffset']) . " before the upcoming week.";


                return $output;
            }
        }
    } catch (PDOException $e) {

        echo "Connection failed: " . $e->getMessage();
    }
    $conn = null;
}
