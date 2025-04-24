
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

// Handle form submission to add a new event
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $location = $_POST['location'] ?? '';
    $datetime = $_POST['datetime'] ?? '';
    $creatorid = $_POST['creatorid'] ?? '';
    $description = $_POST['description'] ?? '';

    // Convert datetime to proper format for MySQL
    $formatted_datetime = date("Y-m-d H:i:s", strtotime($datetime));

    // Inserts data into the table
    $insert_sql = "INSERT INTO event (name, location, datetime, creatorid, description) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_sql);

    if ($stmt) {
        $stmt->bind_param("sssss", $name, $location, $formatted_datetime, $creatorid, $description);
        if ($stmt->execute()) {
            echo "<p>Event added successfully!</p>";
        } else {
            echo "<p>Error adding event: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        echo "<p>Failed to prepare insert statement: " . $conn->error . "</p>";
    }
}

$conn->close();
?>

