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
    $title = $_POST['title'] ?? '';
    $description = $_POST['description'] ?? 
	$opendate = $_POST['opendate'] ?? '';
    $closedate = $_POST['closedate'] ?? '';
    $contactemail = $_POST['contactemail'] ?? '';
	$alumniid = $_POST['alumniid'] ?? '';

// Insert information into table
            $insert_sql = "INSERT INTO job (title, description, opendate, closedate, contactemail, alumniid) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_sql);
            if ($stmt) {
                $stmt->bind_param("ssssss", $title, $description, $opendate, $closedate, $contactemail, $alumniid);
                if ($stmt->execute()) {
                    echo "<p>Event added successfully!</p>";
                } else {
                    echo "<p>Error adding event: " . $stmt->error . "</p>";
                }
            } else {
                echo "<p>Failed to prepare insert statement.</p>";
            }
        } 

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Event</title>
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
            color: #007bff;
        }
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
            color: #555;
        }
        input, textarea {
            width: 90%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        input[type="datetime-local"] {
            width: 90%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            margin-top: 15px;
        }
        input[type="submit"]:hover {
            background-color: #c2185b;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add New Job</h2>
        <form method="post">
            <label>Title:</label>
            <input type="text" name="title" required>
            
            <label>Description:</label>
            <textarea name="location" required></textarea>
			
			<label>Open Date:</label>
            <input type="datetime-local" name="opendate" required>
            
			<label>Close Date:</label>
            <input type="datetime-local" name="closedate" required>
            
            <label>Contact Email:</label>
            <input type="text" name="contactemail" required>
			
		    <label>Alumni ID:</label>
            <textarea name="alumniid" required></textarea>
            
            <input type="submit" value="Add Event">
        </form>
    </div>
</body>
</html>

