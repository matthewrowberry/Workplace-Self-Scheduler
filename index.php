<?php
date_default_timezone_set('America/Denver');

define('APP_RUNNING', true);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    include 'login.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit;
}

$id = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? $_POST['id'] : null;
}
$name = "";

include "check_credentials.php";

if ($id == "" || $name == "") {
    exit;
}


?>



<html>

<head>
    <title>Work Scheduler</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        const days = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
        const dayStart = 1;
        const dayEnd = 6;
        const timeStart = 6
        const timeEnd = 23.5
        const interval = 0.5; // not set up correctly
        const stations = 2;
        var date = new Date('<?php echo (date('N') == 6) ? date('Y-m-d', strtotime('+1 week')) : date('Y-m-d'); ?>');
        var sunday = new Date(date);
        var newReservations = [];
        var removedReservations = [];
        let reservations = <?php

                            require 'config.php';




                            $today = date('N'); // Get day of the week (1 = Monday, 6 = Saturday, 7 = Sunday)
                            if ($today == 6) {
                                $sat = date('Y-m-d', strtotime('today')); // Tomorrow (e.g., October 12, 2025)
                                $sun = date('Y-m-d', strtotime('tomorrow +1 week')); // One week from tomorrow (e.g., October 19, 2025)
                            } else {
                                $sat = date('Y-m-d', strtotime('last saturday')); // Last Saturday
                                $sun = date('Y-m-d', strtotime('next sunday')); // Next Sunday
                            }

                            //PUT ID VERIFICATION HERE

                            try {
                                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
                                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                $sql = $conn->prepare("SELECT name, date, start, end, station FROM reservations WHERE date>:sat AND date<:sun");
                                $sql->bindParam(':sat', $sat);
                                $sql->bindParam(':sun', $sun);



                                $sql->execute();

                                $results = $sql->fetchAll(PDO::FETCH_ASSOC);


                                echo json_encode($results);
                            } catch (PDOException $e) {
                                echo "Connection failed: " . $e->getMessage();
                            }



                            $conn = null; ?>;
        sunday.setDate(date.getDate() - date.getDay());

        const id = <?php echo $id; ?>;
        const username = "<?php echo $name; ?>";
    </script>
    <script src="config.js"></script>
    <script src="script.js" defer></script>

</head>

<body>
    <div id="top-bar">
        <div id="status">
            <span class="upload-status-indicator"></span>
            <p id="status-text">Online</p>
        </div>
        <div id="arrows">
            <button onClick="changeWeek(-1)"><i class="arrow left"></i></button>
            <button onClick="changeWeek(1)"><i class=" arrow right"></i></button>
        </div>
        <div id="restrictions">
            <button class="restriction-indicator" aria-describedby="restriction-tooltip" aria-expanded="false">
                <span class="restriction-indicator"></span> Errors
            </button>

            <div id="restriction-tooltip" class="restriction-tooltip" role="tooltip">
                1 Restriction
            </div>

            <div class="restriction-panel" hidden>
                <ul id="error-list" class="restriction-list" aria-live="polite">
                </ul>
            </div>
        </div>
    </div>
    <div id="alert-overlay" class="alert-overlay">
        <div class="alert-box" role="dialog" aria-modal="true" aria-labelledby="alert-title">
            <button class="alert-close" aria-label="Close">x</button>
            <h2 id="alert-title">Notice</h2>
            <p id="alert-text">Something went wrong</p>
        </div>
    </div>
    <div id="limits">
        <p><?php require 'restriction.php';
            echo getScheduleTime($id); ?></p>
    </div>
    <div id="calendar">
        <table id="table">
            <thead>

            </thead>

        </table>
    </div>



</body>

</html>