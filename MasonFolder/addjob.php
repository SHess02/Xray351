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
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Job</title>
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
<script>
        function validateForm() {
            const form = document.forms["jobForm"];
            const title = form["title"].value.trim();
            const description = form["description"].value.trim();
            const opendate = form["opendate"].value.trim();
            const closedate = form["closedate"].value.trim();
            const contactemail = form["contactemail"].value.trim();
            const alumniid = form["alumniid"].value.trim();

            if (!title || !description || !opendate || !closedate || !contactemail || !alumniid) {
                alert("All fields are required.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Add New Job</h2>
        <form name="jobForm" method="POST" onsubmit="return validateForm();">
            <label for="title">Job Title:</label>
            <input type="text" name="title" id="title" required>

            <label for="description">Description:</label>
            <textarea name="description" id="description" rows="4" required></textarea>

            <label for="opendate">Open Date:</label>
            <input type="date" name="opendate" id="opendate" required>

            <label for="closedate">Close Date:</label>
            <input type="date" name="closedate" id="closedate" required>

            <label for="contactemail">Contact Email:</label>
            <input type="email" name="contactemail" id="contactemail" required>

            <label for="alumniid">Alumni ID:</label>
            <input type="number" name="alumniid" id="alumniid" required>

            <input type="submit" value="Submit Job">
        </form>
    </div>
</body>
</html>

