<?php
if (!defined('APP_RUNNING')) {
    http_response_code(403); // Forbidden
    exit('Direct access not allowed');
}


?>

<html>

<head>
    <title>Work Scheduler</title>
    <link rel="stylesheet" href="styled.css">
</head>

<body>
    <h1>Login</h1>
    <form method="POST" action="index.php">
        <label for="id">ID:</label>
        <input type="text" id="id" name="id" required>
        <br><br>
        <input type="submit" value="Submit">
    </form>
</body>

</html>