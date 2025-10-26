<?php





?>

<html>

<head>
    <title>Work Scheduler</title>
    <link rel="stylesheet" href="styled.css">
</head>

<body>
    <h1>Login</h1>
    <?php
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo "<h2 style=\"color: red\">Incorrect Password</h2>";
    }
    ?>
    <form method="POST" action="index.php">
        <label for="password">Password</label>
        <input type="text" id="password" name="password" required>
        <br><br>
        <input type="submit" value="Submit">
    </form>
</body>

</html>