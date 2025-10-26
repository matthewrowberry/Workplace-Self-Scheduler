<?php
function checkAfter($dayOfWeek, $daysBefore, $hours, $minutes) {
    //I want to check if it's x days/hours/minutes beforethe yth day of the week

    date_default_timezone_set('America/Denver');
    //create a date that is the next sunday
    $date = new DateTime('next Sunday');

    //offset it by the yth day of the week
    $offset = new DateInterval("P" . (string)$dayOfWeek . "D");

    $date->add($offset);
    //subtract x days
    $offset = new DateInterval("P" . (string)$daysBefore . "D");

    $date->sub($offset);

    //add the hours and minutes for the times
    $offset = new DateInterval("P" . (string)$hours . "H" . (string)$minutes . "M");

    $date->add($offset);

    $now = new DateTime();


    return $now>$date;
}


?>