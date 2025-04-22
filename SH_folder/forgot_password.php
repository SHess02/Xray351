<?php
require 'db_connect_temp.php';
require '../EthanWork/mailer.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim(strtolower($_POST['email']));

    $stmt = $conn->prepare("SELECT userid FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($userid);
        $stmt->fetch();
        $stmt->close();

        $reset_token = bin2hex(random_bytes(32));
        $expires_at = date("Y-m-d H:i:s", strtotime("+1 hour"));

        $stmt = $conn->prepare("UPDATE user SET password_reset_token = ?, reset_expires_at = ? WHERE userid = ?");
        $stmt->bind_param("ssi", $reset_token, $expires_at, $userid);
        $stmt->execute();
        $stmt->close();

        sendPasswordResetEmail($email, $reset_token);

    $message = "<p style='color: black; text-align: center;'>If this email is registered, a reset link has been sent.</p>";
	}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: #fff;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .forgot-password-form {
            background: #ffffff;
            color: #000;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        .form-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .form-control:focus {
            box-shadow: 0 0 10px rgba(38, 143, 255, 0.5);
            border-color: #268fff;
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
    <div class="forgot-password-form">
        <div class="form-header">
            <h2>Reset Your Password</h2>
        </div>
        <form method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Enter Your CNU Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="yourname@cnu.edu" required>
            </div>
            <div class="btn-container">
                <button type="submit" class="btn btn-custom">Send Reset Link</button>
            </div>
        </form>
		<?php if (!empty($message)) echo $message; ?>
	</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>