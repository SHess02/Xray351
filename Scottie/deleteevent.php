<?php
include '../includes/includes.php';

$db = new Database();

// Database connection settings
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

// Handle event deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eventid'])) {
    $eventid = intval($_POST['eventid']);
    $delete_sql = "DELETE FROM event WHERE eventid = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    
    if ($delete_stmt) {
        $delete_stmt->bind_param("i", $eventid);
        if ($delete_stmt->execute()) {
            echo "<p>Event deleted successfully!</p>";
        } else {
            echo "<p>Error deleting event.</p>";
        }
    } else {
        echo "<p>Failed to prepare delete statement.</p>";
    }
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
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
            color: #555;
        }
        input {
            width: 90%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        input[type="submit"] {
            background-color: #d9534f;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            margin-top: 15px;
        }
        input[type="submit"]:hover {
            background-color: #c9302c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2> Delete Event </h2>
        <form method="post">
            <label> Event ID: </label>
            <input type="number" name="eventid" required>
            <input type="submit" value="Delete Event">
        </form>
    </div>
</body>
</html>
