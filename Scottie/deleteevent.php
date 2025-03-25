<?php
include '../includes/includes.php';

$servername = "localhost";
$userName = "root";
$passWord = "";
$dbname = "alumniconnectdb";

// Create connection
$conn = new mysqli($servername, $userName, $passWord, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize message variable
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['eventid'])) {
    $eventid = filter_input(INPUT_GET, 'eventid', FILTER_VALIDATE_INT);

    if ($eventid && $eventid > 0) {
        $delete_sql = "DELETE FROM event WHERE eventid = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        
        if ($delete_stmt) {
            $delete_stmt->bind_param("i", $eventid);
            if ($delete_stmt->execute()) {
                $message = "<p style='color: green;'>Event deleted successfully!</p>";
            } else {
                $message = "<p style='color: red;'>Error deleting event: " . $delete_stmt->error . "</p>";
            }
            $delete_stmt->close();
        } else {
            $message = "<p style='color: red;'>Failed to prepare delete statement.</p>";
        }
    } else {
        $message = "<p style='color: red;'>Invalid Event ID.</p>";
    }
} else {
    $message = "<p style='color: red;'>No Event ID provided.</p>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Event</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            margin: 0;
            padding: 0;
        }
        .container {
            background: white;
            width: 50%;
            margin: 50px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        h2 {
            color: #d9534f;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Delete Event</h2>
        <?= $message; ?>
    </div>
</body>
</html>
