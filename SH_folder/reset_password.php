<?php
require 'db_connect_temp.php'; // Adjust path if needed

// Show errors (for debugging)
error_reporting(E_ALL);
ini_set('display_errors', 1);

$show_form = false;
$error = '';
$token = $_GET['token'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($token)) {
    // Step 1: Validate token
    $stmt = $conn->prepare("SELECT userid, reset_expires_at FROM user WHERE password_reset_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($userid, $expires_at);
        $stmt->fetch();
        if (strtotime($expires_at) >= time()) {
            // Token is valid and not expired
            $show_form = true;
        } else {
            $error = "Reset link has expired.";
        }
    } else {
        $error = "Invalid reset link.";
    }
    $stmt->close();
}

// Step 2: Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($password !== $confirm) {
        $error = "Passwords do not match.";
        $show_form = true;
    } else {
        // Hash and update password
        $hashed = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("UPDATE user SET password = ?, password_reset_token = NULL, reset_expires_at = NULL WHERE password_reset_token = ?");
        $stmt->bind_param("ss", $hashed, $token);
        $stmt->execute();

        echo "<p style='color: green;'>Password has been reset successfully. You can now <a href='login.php'>log in</a>.</p>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center" style="min-height: 100vh;">
<div class="card p-4 shadow-sm" style="width: 100%; max-width: 400px;">
    <h3 class="mb-3 text-center">Set New Password</h3>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <?php if ($show_form): ?>
        <form method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <div class="mb-3">
                <label for="password" class="form-label">New Password</label>
                <input type="password" name="password" class="form-control" required minlength="8">
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" required minlength="8">
            </div>
            <button type="submit" class="btn btn-primary w-100">Reset Password</button>
        </form>
    <?php elseif (!$error): ?>
        <p class="text-danger text-center">Invalid or missing reset token.</p>
    <?php endif; ?>
</div>
</body>
</html>