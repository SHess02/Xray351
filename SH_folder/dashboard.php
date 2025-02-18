<?php
// Start the session to access user data
session_start();

// Check if the user is logged in by checking the session variable
if (!isset($_SESSION['usernameinput'])) {
    // If not logged in, redirect to login page
    header("Location: login.php");  // Redirect to login.php
    exit();
}

// Get the username from the session
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
