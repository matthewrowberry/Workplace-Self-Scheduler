<?php
$today = date('N'); // Get day of the week (1 = Monday, 6 = Saturday, 7 = Sunday)
$sat = ($today == 6) ? date('Y-m-d', strtotime('+1 week')) : date('Y-m-d', strtotime('last saturday'));
$sun = ($today == 6) ? date('Y-m-d', strtotime('next sunday +1 week')) : date('Y-m-d', strtotime('next sunday'));

echo $sat;
echo $sun;


$today = date('N'); // Get day of the week (1 = Monday, 6 = Saturday, 7 = Sunday)
$sat = ($today == 6) ? date('Y-m-d', strtotime('+1 week')) : date('Y-m-d', strtotime('last saturday'));
$sun = ($today == 6) ? date('Y-m-d', strtotime('next sunday +1 week')) : date('Y-m-d', strtotime('next sunday'));


$sat = date('Y-m-d', strtotime('last saturday'));
$sun = date('Y-m-d', strtotime('next sunday'));
