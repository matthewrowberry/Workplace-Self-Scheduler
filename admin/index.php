<?php
define('APP_RUNNING', true);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if (!isset($_COOKIE['loggedIn']) || $_COOKIE['loggedIn'] !== 'abc123') {
        include 'login.php';
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = isset($_POST['password']) ? $_POST['password'] : null;

    include "auth.php";

    if ($password != $authcode) {
        include "../config.php";

        $redirectUrl = $url . "/admin/login.php";

        // Set the Location header with a 307 Temporary Redirect (preserves POST method)
        header("Location: $redirectUrl", true, 307);

        // Exit to ensure no further code is executed
        exit;
    }
}

setcookie('loggedIn', 'abc123', time() + 3600, '/', 'mrowberry.com', true, true);

require '../config.php';
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "CREATE TABLE IF NOT EXISTS startTimes (id INT PRIMARY KEY, startFromWeekday INT, prevDaysOffset INT, hour INT, minute INT)";
    $conn->exec($sql);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

$conn = null;




?>

<html>

<head>
    <title>Admin Panel</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        const dropdown = "<?php require "dropdown.php"; ?>";
        let startTimes = <?php echo json_encode(require "getStartTimes.php"); ?>;
        const idMap = <?php echo json_encode(require "idMap.php"); ?>;
        const idToName = new Map(idMap.map(u => [u.id, u.name]));
    </script>
    <script src="../config.js"></script>
    <script src="script.js" defer></script>
</head>

<body>
    <div id="restrictions">
        <form id="restrictionForm" onsubmit="return addTimeBlock(event)">
            <table id="startRestrictions">
                <thead id="tableHeader">
                    <tr id="tableHeaderRow">
                        <th>Name</th>
                        <th>Start from Weekday</th>
                        <th>Previous days offset</th>
                        <th>Hours</th>
                        <th>Minutes</th>
                    </tr>
                </thead>
                <thead id="startRestrictionsBody">

                </thead>
                <tfoot>
                    <tr id="new">
                        <td><button type="button" onclick="addRow()">Add Entry</button></td>
                    </tr>
                </tfoot>
            </table>


        </form>
    </div>


</body>



</html>