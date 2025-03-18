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

// Fetch event details if ID is provided
if (isset($_GET['eventid'])) {
    $eventid = intval($_GET['eventid']);
    $sql = "SELECT * FROM event WHERE eventid= $eventid";
    $result = $conn->query($sql);
    $event = $result->fetch_assoc();
}

// Handle form submission to update only specified fields
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fields = [];
    $params = [];
    $types = "";
    
    if (!empty($_POST['name'])) {
        $fields[] = "name = ?";
        $params[] = $_POST['name'];
        $types .= "s";
    }
    if (!empty($_POST['location'])) {
        $fields[] = "location = ?";
        $params[] = $_POST['location'];
        $types .= "s";
    }
    if (!empty($_POST['datetime'])) {
        $datetime = date("Y-m-d H:i:s", strtotime($_POST['datetime']));
        $fields[] = "datetime = ?";
        $params[] = $datetime;
        $types .= "s";
    }
    if (!empty($_POST['description'])) {
        $fields[] = "description = ?";
        $params[] = $_POST['description'];
        $types .= "s";
    }
	
    
    if (!empty($fields)) {
        $update_sql = "UPDATE event SET " . implode(", ", $fields) . " WHERE eventid = ?";
        $params[] = $eventid;
        $types .= "i";
        
        $update_stmt = $conn->prepare($update_sql);
        if ($update_stmt) {
            $update_stmt->bind_param($types, ...$params);
            if ($update_stmt->execute()) {
                echo "<p>Event details updated successfully!</p>";
                // Refresh event data after update
                $sql = "SELECT * FROM event WHERE eventid = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $eventid);
                $stmt->execute();
                $result = $stmt->get_result();
                $event = $result->fetch_assoc();
            } else {
                echo "<p>Error updating event details.</p>";
            }
        } else {
            echo "<p>Failed to prepare update statement.</p>";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
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
        <h2> Edit Event Details </h2>
        <form method="post">
            <input type="hidden" name="eventid" value="<?php echo $event['eventid']; ?>">
            <label> Event Name: </label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($event['name']); ?>">
            
            <label> Location: </label>
            <textarea name="location"><?php echo htmlspecialchars($event['location']); ?></textarea>
            
            <label> Event Date & Time: </label>
            <input type="datetime-local" name="datetime" value="<?php echo isset($event['datetime']) ? date('Y-m-d\TH:i', strtotime($event['datetime'])) : ''; ?>">
            
            <label> Description: </label>
            <input type="text" name="description" value="<?php echo htmlspecialchars($event['description']); ?>">
			
            <input type="submit" value="Update">
        </form>
    </div>
</body>
</html>
