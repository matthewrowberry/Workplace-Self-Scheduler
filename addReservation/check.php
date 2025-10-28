<?php
require "../datelock/index.php";
function check($id, $name, $date, $start, $end,$station){

    $sunday = new DateTime('next Sunday');
    $reservationDate = new DateTime($date);
    if($sunday<$reservationDate){

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = $conn->prepare("SELECT id, startFromWeekday, prevDaysOffset, hour, minute FROM startTimes WHERE id = :id");
            $sql->bindParam(':id',$id);
            $sql->execute();

            $row = $sql->fetchAll(PDO::FETCH_ASSOC);

            if($row){
                if(checkAfter($row['startFromWeekday']))
            }

            return $results;
        } catch (PDOException $e) {

            echo "Connection failed: " . $e->getMessage();
        }



        $conn = null;

        checkAfter($dayOfWeek, $daysBefore, $hours, $minutes) {}
    
    return 0;
}

?>