<?php
session_start();
include 'db_connect_temp.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim(strtolower($_POST['email']));
    $securityans1 = trim(strtolower($_POST['securityans1']));
    $securityans2 = trim(strtolower($_POST['securityans2']));

    if (!empty($email) && !empty($securityans1) && !empty($securityans2)) {
        $stmt = $conn->prepare("SELECT userid FROM user WHERE email = ? AND securityans1 = ? AND securityans2 = ?");
        $stmt->bind_param("sss", $email, $securityans1, $securityans2);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($userid);
            $stmt->fetch();

            $token = bin2hex(random_bytes(32));

            $_SESSION['reset_token'] = $token;
            $_SESSION['reset_user_id'] = $userid;

            header("Location: reset_password.php?token=$token");
            exit();
        } else {
            echo "Invalid email or security answers. Please try again.";
        }

        $stmt->close();
    } else {
        echo "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password : QuickServe Reservations</title>
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

        .forgot-password-form {
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

    <div class="forgot-password-form">
        <div class="text-center">
            <h2>Forgot Password</h2>
        </div>
		<br>
        <form method="post">
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="mb-3">
                <label for="security_a1" class="form-label">Security Question #1:<br>What was the year, make, and model of your first car?</label>
                <input type="text" class="form-control" id="securityans1" name="securityans1" required>
            </div>

            <div class="mb-3">
                <label for="security_a2" class="form-label">Security Question #2:<br>What was your childhood nickname?</label>
                <input type="text" class="form-control" id="securityans2" name="securityans2" required>
            </div>

            <div class="btn-container">
                <button type="submit" class="btn btn-custom">Verify</button>
            </div>
        </form>
    </div>

</body>
</html>