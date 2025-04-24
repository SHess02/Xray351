<!-- Scottie Sage -->
<?php
session_start();
include '../includes/session_check.php';
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
	$creatorid = $_SESSION['userid'];
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
<!DOCTYPE html>
 <html lang="en">
 <head>
     <meta charset="UTF-8">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <title>Add Event</title>
            <style>
		* {
    font-family: Arial, sans-serif;
}
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
		.container form {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.container form label,
.container form input,
.container form textarea {
    width: 90%;
    max-width: 500px;
    margin-bottom: 15px;
}

.container form input[type="submit"] {
    width: 50%;
}
    </style>
 </head>
 <body>
     <div class="container">
         <h2>Add New Event</h2>
         <form method="post">
             <label>Event Name:</label>
             <input type="text" name="name" required>
             
             <label>Location:</label>
             <textarea name="location" required></textarea>
             
             <label>Event Date & Time:</label>
             <input type="datetime-local" name="datetime" required>
             
             <label>Description</label>
             <textarea name="description" required></textarea>
 
             <input type="submit" value="Add Event">
         </form>
     </div>
 </body>
 </html>