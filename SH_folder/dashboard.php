<?php

session_start();

if (!isset($_SESSION['usernameinput'])) {

    header("Location: login.php");  
    exit();
}

$usernameinput = $_SESSION['usernameinput'];
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Welcome to AlumniConnect Dashboard</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        
        <h1>Welcome, <?php echo htmlspecialchars($usernameinput); ?>!</h1>
        <p>You are logged in to your AlumniConnect dashboard.</p>

        <a href="logout.php">Logout</a>  <!-- Link to logout -->

    </body>
</html>
