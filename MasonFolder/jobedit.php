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

// Fetch company details if ID is provided
if (isset($_GET['jobid'])) {
    $jobid = intval($_GET['jobid']);
    $sql = "SELECT * FROM job WHERE jobid= $jobid";
    $result = $conn->query($sql);
    $job = $result->fetch_assoc();
}

// Handle form submission to update only specified fields
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fields = [];
    $params = [];
    $types = "";
    
    if (isset($_POST['title']) && $_POST['title'] !== "") {
        $fields[] = "title = ?";
        $params[] = $_POST['title'];
        $types .= "s";
    }
    if (isset($_POST['description']) && $_POST['description'] !== "") {
        $fields[] = "description = ?";
        $params[] = $_POST['description'];
        $types .= "s";
    }
    if (isset($_POST['opendate']) && $_POST['opendate'] !== "") {
        $fields[] = "opendate = ?";
        $params[] = $_POST['opendate'];
        $types .= "s";
    }
    if (isset($_POST['closedate']) && $_POST['closedate'] !== "") {
        $fields[] = "closedate = ?";
        $params[] = $_POST['closedate'];
        $types .= "s";
    }  
    if (isset($_POST['contactemail']) && $_POST['contactemail'] !== "") {
        $fields[] = "contactemail = ?";
        $params[] = $_POST['contactemail'];
        $types .= "s";
    }  
    if (!empty($fields)) {
        $update_sql = "UPDATE job SET " . implode(", ", $fields) . " WHERE jobid = ?";
        $params[] = $jobid;
        $types .= "i";
        
        $update_stmt = $conn->prepare($update_sql);
        if ($update_stmt) {
            $update_stmt->bind_param($types, ...$params);
            if ($update_stmt->execute()) {
                echo "<p>Job details updated successfully!</p>";
            if ($update_stmt->execute()) {
				echo "<p>Job details updated successfully!</p>";
    // Refresh job data after update
    $sql = "SELECT * FROM job WHERE jobid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $jobid);
    $stmt->execute();
    $result = $stmt->get_result();
    $job = $result->fetch_assoc();
} else {
    echo "<p>Error updating job details.</p>";
}
            } else {
                echo "<p>Error updating job details.</p>";
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
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Job</title>
</head>
<body>
    <div class="container">
        <h2>Edit Job Details</h2>
        <form method="post">
            <input type="hidden" name="jobid" value="<?php echo $job['jobid']; ?>">

            <label>Title:</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($job['title']); ?>">

            <label>Description:</label>
            <textarea name="description"><?php echo htmlspecialchars($job['description']); ?></textarea>

            <label>Open Date:</label>
            <input type="date" name="opendate" value="<?php echo htmlspecialchars($job['opendate']); ?>">

            <label>Close Date:</label>
            <input type="date" name="closedate" value="<?php echo htmlspecialchars($job['closedate']); ?>">

            <label>Contact Email:</label>
            <input type="email" name="contactemail" value="<?php echo htmlspecialchars($job['contactemail']); ?>">

            <input type="submit" value="Update">
        </form>
    </div>
</body>
</html>
