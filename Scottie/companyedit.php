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
if (isset($_GET['companyid'])) {
    $companyid = intval($_GET['companyid']);
    $sql = "SELECT * FROM company WHERE companyid = $companyid";
    $result = $conn->query($sql);
    $company = $result->fetch_assoc();
}

// Handle form submission to update only specified fields
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fields = [];
    $params = [];
    $types = "";
    
    if (isset($_POST['name']) && $_POST['name'] !== "") {
        $fields[] = "name = ?";
        $params[] = $_POST['name'];
        $types .= "s";
    }
    if (isset($_POST['description']) && $_POST['description'] !== "") {
        $fields[] = "description = ?";
        $params[] = $_POST['description'];
        $types .= "s";
    }
    if (isset($_POST['activelistings']) && $_POST['activelistings'] !== "") {
        $fields[] = "activelistings = ?";
        $params[] = (int)$_POST['activelistings'];
        $types .= "i";
    }
    
    if (!empty($fields)) {
        $update_sql = "UPDATE company SET " . implode(", ", $fields) . " WHERE companyid = ?";
        $params[] = $companyid;
        $types .= "i";
        
        $update_stmt = $conn->prepare($update_sql);
        if ($update_stmt) {
            $update_stmt->bind_param($types, ...$params);
            if ($update_stmt->execute()) {
                echo "<p>Company details updated successfully!</p>";
            if ($update_stmt->execute()) {
				echo "<p>Company details updated successfully!</p>";
    // Refresh company data after update
    $sql = "SELECT * FROM company WHERE companyid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $companyid);
    $stmt->execute();
    $result = $stmt->get_result();
    $company = $result->fetch_assoc();
} else {
    echo "<p>Error updating company details.</p>";
}
            } else {
                echo "<p>Error updating company details.</p>";
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
        <h2>Edit Company Details</h2>
        <form method="post">
            <input type="hidden" name="companyid" value="<?php echo $company['companyid']; ?>">
            <label>Name:</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($company['name']); ?>">
            
            <label>Description:</label>
            <textarea name="description"><?php echo htmlspecialchars($company['description']); ?></textarea>
            
            <label>Active Listings:</label>
            <input type="number" name="activelistings" value="<?php echo $company['activelistings']; ?>">
            
            <input type="submit" value="Update">
        </form>
    </div>
</body>
</html>