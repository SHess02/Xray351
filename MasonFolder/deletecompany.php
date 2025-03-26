<?php
include '../includes/includes.php';

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

// Initialize message variable
$message = "";

// Only proceed if a company ID is provided in the GET request
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['companyid'])) {
    $companyid = filter_input(INPUT_GET, 'companyid', FILTER_VALIDATE_INT);

    if ($companyid && $companyid > 0) {
        // First, delete any references to this company in the favorite_company table
        $delete_favorite_sql = "DELETE FROM favorite_company WHERE companyid = ?";
        $delete_favorite_stmt = $conn->prepare($delete_favorite_sql);

        if ($delete_favorite_stmt) {
            $delete_favorite_stmt->bind_param("i", $companyid);
            if ($delete_favorite_stmt->execute()) {
                // Now delete the company
                $delete_sql = "DELETE FROM company WHERE companyid = ?";
                $delete_stmt = $conn->prepare($delete_sql);

                if ($delete_stmt) {
                    $delete_stmt->bind_param("i", $companyid);
                    if ($delete_stmt->execute()) {
                        $message = "Company deleted successfully!";
                    } else {
                        $message = "<p style='color: red;'>Error deleting company. Please try again later.</p>";
                        error_log("Error deleting company with ID $companyid: " . $delete_stmt->error);
                    }
                    $delete_stmt->close();
                } else {
                    $message = "<p style='color: red;'>Failed to prepare delete statement for company.</p>";
                }
            } else {
                $message = "<p style='color: red;'>Error deleting references in favorite_company table: " . $delete_favorite_stmt->error . "</p>";
            }
            $delete_favorite_stmt->close();
        } else {
            $message = "<p style='color: red;'>Failed to prepare delete statement for favorite_company table.</p>";
        }
    } else {
        $message = "<p style='color: red;'>Invalid Company ID.</p>";
    }
} else {
    $message = "<p style='color: red;'>No Company ID provided.</p>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Company</title>
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
            color: #d9534f;
        }
        .message {
            margin-top: 15px;
            font-size: 16px;
        }
        .message p {
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Delete Company</h2>
        <div class="message">
            <?= htmlspecialchars($message); ?>
        </div>
    </div>
</body>
</html>