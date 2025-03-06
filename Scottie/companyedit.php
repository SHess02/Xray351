<?php
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
    $sql = "SELECT * FROM companies WHERE companyid = $companyid";
    $result = $conn->query($sql);
    $company = $result->fetch_assoc();
}

// Handle form submission to update only specified fields
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $companyid = intval($_POST['companyid']);
    $update_fields = [];
    
    if (!empty($_POST['name'])) {
        $name = $conn->real_escape_string($_POST['name']);
        $update_fields[] = "name='$name'";
    }
    if (!empty($_POST['description'])) {
        $description = $conn->real_escape_string($_POST['description']);
        $update_fields[] = "description='$description'";
    }
    if (isset($_POST['activelistings']) && $_POST['activelistings'] !== '') {
        $activelistings = intval($_POST['activelistings']);
        $update_fields[] = "activelistings=$activelistings";
    }
    if (!empty($_POST['admin_email'])) {
        $admin_email = $conn->real_escape_string($_POST['admin_email']);
        $update_fields[] = "admin_email='$admin_email'";
    }
    
    if (!empty($update_fields)) {
        $update_sql = "UPDATE companies SET " . implode(", ", $update_fields) . " WHERE companyid=$companyid";
        if ($conn->query($update_sql) === TRUE) {
            echo "Record updated successfully";
        } else {
            echo "Error updating record: " . $conn->error;
        }
    } else {
        echo "No fields provided for update.";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Company</title>
</head>
<body>
    <h2>Edit Company Details</h2>
    <form method="post">
        <input type="hidden" name="companyid" value="<?php echo $company['companyid']; ?>">
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($company['name']); ?>"><br>
        <label>Description:</label>
        <textarea name="description"><?php echo htmlspecialchars($company['description']); ?></textarea><br>
        <label>Active Listings:</label>
        <input type="number" name="activelistings" value="<?php echo $company['activelistings']; ?>"><br>
        <label>Admin Email:</label>
        <input type="email" name="admin_email" value="<?php echo htmlspecialchars($company['admin_email']); ?>"><br>
        <input type="submit" value="Update">
    </form>
    <br>
</body>
</html>