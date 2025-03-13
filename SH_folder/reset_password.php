<?php
session_start();
include 'db_connect_temp.php';

if (!isset($_SESSION['reset_token']) || !isset($_SESSION['reset_user_id']) || $_GET['token'] !== $_SESSION['reset_token']) {
    die("Invalid or expired reset link.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (!empty($new_password) && $new_password === $confirm_password) {
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        $stmt = $conn->prepare("UPDATE user SET password = ? WHERE userid = ?");
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset : QuickServe Reservations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: #fff;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .reset-password-form {
            background: #ffffff;
            color: #000;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        .form-label {
            font-weight: bold;
        }

        .btn-custom {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: #fff;
            border: none;
            transition: background 0.3s ease;
            width: 100%;
        }

        .btn-container {
            display: flex;
            justify-content: center;
            margin-top: 1rem;
        }
    </style>
</head>

<body>

    <div class="reset-password-form">
        <div class="text-center">
            <h2>Reset Password</h2>
        </div>
		<br>
        <form action="reset_password.php?token=<?= htmlspecialchars($_GET['token']) ?>" method="post">
            <div class="mb-3">
                <label for="new_password" class="form-label">New Password:</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>

            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password:</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            </div>

            <div class="btn-container">
                <button type="submit" class="btn btn-custom">Reset</button>
            </div>
        </form>
    </div>

</body>
</html>