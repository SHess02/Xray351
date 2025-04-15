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
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? '';
    $opendate = $_POST['opendate'] ?? '';
    $closedate = $_POST['closedate'] ?? '';
    $contactemail = $_POST['contactemail'] ?? '';
    $alumniid = $_POST['alumniid'] ?? '';

    // Validate alumniid exists
    $check_sql = "SELECT id FROM alumni WHERE id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $alumniid);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        // Proceed with insert
        $insert_sql = "INSERT INTO job (title, description, opendate, closedate, contactemail, alumniid) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        if ($stmt) {
            $stmt->bind_param("ssssss", $title, $description, $opendate, $closedate, $contactemail, $alumniid);
            if ($stmt->execute()) {
                echo "<p>Job added successfully!</p>";
            } else {
                echo "<p>Error adding job: " . $stmt->error . "</p>";
            }
            $stmt->close();
        } else {
            echo "<p>Failed to prepare insert statement: " . $conn->error . "</p>";
        }
    } else {
        echo "<p style='color:red;'>Error: The provided Alumni ID does not exist.</p>";
    }

    $check_stmt->close();
}

$conn->close();
?>
