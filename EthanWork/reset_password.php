<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['reset_token']) || !isset($_SESSION['reset_user_id']) || $_GET['token'] !== $_SESSION['reset_token']) {
    die("Invalid or expired reset link.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (!empty($new_password) && $new_password === $confirm_password) {
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("UPDATE user SET password = ? WHERE user_id = ?");
        $stmt->bind_param("si", $hashed_password, $_SESSION['reset_user_id']);

        if ($stmt->execute()) {
            $stmt->close();

            session_destroy();

            header("Location: login.php?reset=success");
            exit();
        } else {
            echo "Error updating password.";
        }
    } else {
        echo "Passwords do not match.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">
    <title>Password Reset : QuickServe Reservations</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <h2>Password Reset</h2><br>
    <form action="reset_password.php?token=<?= htmlspecialchars($_GET['token']) ?>" method="post">
        <label><b>New Password:</b></label><br>
        <input type="password" name="new_password" required><br><br>
        
        <label><b>Confirm Password:</b></label><br>
        <input type="password" name="confirm_password" required><br><br>
        
        <button class="btn btn-primary">Reset</button>
    </form>
</body>
</html>