
<?php
session_start();
include '../includes/session_check.php';
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

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['jobid'])) {
    $jobid = filter_input(INPUT_GET, 'jobid', FILTER_VALIDATE_INT);

    if ($jobid && $jobid > 0) {
        // Step 1: Delete any references to the job in the application table
        $delete_application_sql = "DELETE FROM application WHERE jobid = ?";
        $delete_application_stmt = $conn->prepare($delete_application_sql);

        if ($delete_application_stmt) {
            $delete_application_stmt->bind_param("i", $jobid);
            if (!$delete_application_stmt->execute()) {
                $message = "<p style='color: red;'>Error deleting references in application table: " . $delete_application_stmt->error . "</p>";
            }
            $delete_application_stmt->close();
        }

        // Step 2: Delete any references to the job in the favorite_job table
        $delete_favorite_sql = "DELETE FROM favorite_job WHERE jobid = ?";
        $delete_favorite_stmt = $conn->prepare($delete_favorite_sql);

        if ($delete_favorite_stmt) {
            $delete_favorite_stmt->bind_param("i", $jobid);
            if (!$delete_favorite_stmt->execute()) {
                $message = "<p style='color: red;'>Error deleting references in favorite_job table: " . $delete_favorite_stmt->error . "</p>";
            }
            $delete_favorite_stmt->close();
        }

        // Step 3: Now delete the job from the job table
        $delete_sql = "DELETE FROM job WHERE jobid = ?";
        $delete_stmt = $conn->prepare($delete_sql);

        if ($delete_stmt) {
            $delete_stmt->bind_param("i", $jobid);
            if ($delete_stmt->execute()) {
                $message = "Company deleted successfully!";
            } else {
                $message = "<p style='color: red;'>Error deleting job. Please try again later.</p>";
                error_log("Error deleting job with ID $jobid: " . $delete_stmt->error);
            }
            $delete_stmt->close();
        } else {
            $message = "<p style='color: red;'>Failed to prepare delete statement for job.</p>";
        }
    } else {
        $message = "<p style='color: red;'>Invalid Job ID.</p>";
    }
} else {
    $message = "<p style='color: red;'>No Job ID provided.</p>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Job</title>
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
        <h2>Delete Job</h2>
        <div class="message">
            <?= htmlspecialchars($message); ?>
        </div>
    </div>
</body>
</html>


