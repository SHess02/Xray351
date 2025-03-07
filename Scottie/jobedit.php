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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $jobid = intval($_POST['jobid']);
    $update_fields = [];
    
    if (!empty($_POST['title'])) {
        $title = $conn->real_escape_string($_POST['title']);
        $update_fields[] = "title='$title'";
    }
    if (!empty($_POST['description'])) {
        $description = $conn->real_escape_string($_POST['description']);
        $update_fields[] = "description='$description'";
    }
    if (!empty($update_fields)) {
        $update_sql = "UPDATE job SET " . implode(", ", $update_fields) . " WHERE jobid=$jobid";
        if ($conn->query($update_sql) === TRUE) {
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        echo "No fields provided for update.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<nav class="navbar">
    <button class="nav-btn back-btn" onclick="window.location.href='browsingtab.php'">&#8592;</button>
    <a href="browsingtab.php" class="nav-btn">Recent</a>
	<a href="browsingtab.php" class="nav-btn">Jobs</a>
	<a href="browsingtab.php" class="nav-btn">Companies</a>
	<a href="browsingtab.php" class="nav-btn">Events</a>

    
    <div class="search-container">
        <input type="text" class="search-bar" placeholder="Search...">
    </div>
	
	<a href="browsingtab.php" class="nav-btn">Recent</a>

    <div class="nav-right">
        <button class="nav-btn">🔔</button>
        <button class="nav-btn profile-btn">👤</button>
    </div>
</nav>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Company</title>
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
			font-family: Arial,sans-serif;
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
        <h2> Edit Job Details </h2>
        <form method="post">
            <input type="hidden" name="jobid" value="<?php echo $job['jobid']; ?>">
            <label> Title: </label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($job['title']); ?>">
            
            <label> Description: </label>
            <textarea name="description"><?php echo htmlspecialchars($job['description']); ?></textarea>
            
            <label> Open Date: </label>
            <input type="datetime" name="opendate" value="<?php echo $job['opendate']; ?>">
            
            <input type="submit" value="Update">
        </form>
    </div>
</body>
</html>