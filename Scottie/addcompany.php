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
    $description = $_POST['description'] ?? ''; 
    $activelistings = $_POST['activelistings'] ?? '';
    $creatorid = $_POST['creatorid'] ?? '';

    // Insert information into table
    $insert_sql = "INSERT INTO company (name, description, activelistings, creatorid) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_sql);
    if ($stmt) {
        $stmt->bind_param("ssss", $name, $description, $activelistings, $creatorid);
        if ($stmt->execute()) {
            echo "<p>Company added successfully!</p>";
        } else {
            echo "<p>Error adding company: " . $stmt->error . "</p>";
        }
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
    <title>Add Company</title>
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
        input[type="number"], input[type="text"], input[type="datetime-local"] {
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
    <script>
        // JavaScript validation
        function validateForm() {
            const name = document.forms["companyForm"]["name"].value;
            const description = document.forms["companyForm"]["description"].value;
            const creatorid = document.forms["companyForm"]["creatorid"].value;

            if (name === "" || description === "" || creatorid === "") {
                alert("Name, Description, and Creator ID are required fields.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Add New Company</h2>
        <form name="companyForm" method="post" onsubmit="return validateForm()">
            <label>Name:</label>
            <input type="text" name="name" required>
            
            <label>Description:</label>
            <textarea name="description" required></textarea>
            
            <label>Active Listings:</label>
            <input type="number" name="activelistings">
            
            <label>Creator ID:</label>
            <input type="text" name="creatorid" required>
            
            <input type="submit" value="Add Company">
        </form>
    </div>
</body>
</html>
