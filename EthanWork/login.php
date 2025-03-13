<?php
session_start();
include 'db_connect_temp.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim(strtolower($_POST['email']));
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT userid, password FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            $_SESSION['userid'] = $user_id;
            header("Location: ../MasonFolder/browsingtab.php");
            exit();
        } else {
            echo "Password does not match!";
        }
    } else {
        echo "Invalid email!";
    }
    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Login : QuickServe Reservations</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: #fff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .login-container {
            background: #ffffff;
            color: #000;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        .form-control:focus {
            box-shadow: 0 0 10px rgba(38, 143, 255, 0.5);
            border-color: #268fff;
        }

        .form-header {
            text-align: center;
            margin-bottom: 1.5rem;
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

        .text-muted {
            font-size: 0.8rem;
            text-align: center;
            margin-top: 1rem;
        }

        .center-image {
            max-width: 60%;
            height: auto;
        }
    </style>
</head>

<body>

    <h1>Alumni Connect</h1>
	<br>
    <div class="text-center">
        <img src="cnu_logo.jpg" class="center-image" alt="Logo">
    </div>

    <br>

    <div class="login-container">
        <div class="form-header">
            <h2>Login</h2>
        </div>

        <form action="login.php" method="post">
            <div class="mb-3">
                <label for="email" class="form-label"><b>Email:</b></label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label"><b>Password:</b></label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>

            <div class="btn-container">
                <button type="submit" class="btn btn-custom">Login</button>
            </div>
        </form>

        <div class="text-muted">
            <a href="forgot_password.php" class="text-primary">Forgot password?</a> <br>
            <a href="register.php" class="text-primary">New user? Register here</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>