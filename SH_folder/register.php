<?php
session_start();

$servername = "localhost"; 
$username = "root";
$password = ""; 
$dbname = "alumniconnectdb"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim(strtolower($_POST['email']));
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $security_a1 = trim(strtolower($_POST['security_a1']));
    $security_a2 = trim(strtolower($_POST['security_a2']));



    if (!preg_match("/^[a-zA-Z0-9._%+-]+@cnu\.edu$/", $email)) {
        echo "Invalid email. You must use a @cnu.edu email address.";
        exit();
    }

    if (
        strlen($name) > 0 && strlen($name) <= 45 &&
        strlen($email) > 0 && strlen($email) <= 45 &&
        strlen($confirm_password) >= 8 && strlen($confirm_password) <= 255 &&
        strlen($security_a1) > 0 && strlen($security_a1) <= 100 &&
        strlen($security_a2) > 0 && strlen($security_a2) <= 100) {
        
		if ($password !== $confirm_password) {
            echo "Passwords do not match";
        } else {
            $stmt = $conn->prepare("SELECT userid FROM user WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                echo "Email already exists";
            } else {
                $stmt->close();

                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $conn->prepare("INSERT INTO user (email, role, name, password, securityans1, securityans2) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssss", $email, $role, $name, $hashed_password, $security_a1, $security_a2);

                if ($stmt->execute()) {
                    header("Location: login.php"); // Fixed redirect
                    exit();
                } else {
                    echo "Error: " . $conn->error;
                }
            }
            $stmt->close();
        }
    } else {
        echo "Invalid input. Ensure all fields are within the required length.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration Form</title>
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
        .registration-form {
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
        .form-header h2 {
            font-weight: bold;
        }
        .form-header p {
            font-size: 0.9rem;
            color: #6c757d;
        }
        .btn-custom {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: #fff;
            border: none;
            transition: background 0.3s ease;
        }
        .text-muted {
            font-size: 0.8rem;
            text-align: center;
            margin-top: 1rem;
        }
        .error {
            color: red;
            font-size: 1rem;
            text-align: center;
        }
        .success {
            color: green;
			font-weight: bold;
            font-size: 2rem;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="registration-form">
        <div class="form-header">
            <h2>Register Your Account</h2>
        </div>
        <form method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
            </div>
			<div>
			<label for="student">Student</label>
			<input type="radio" id="student" name="toggle" value="student">

			<label for="alumni">Alumni</label>
			<input type="radio" id="alumni" name="toggle" value="alumni">
			</div> <br>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Create a password" required>
            </div>
            <div class="mb-3">
                <label for="confirm-password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirm-password" name="confirm_password" placeholder="Confirm your password" required>
            </div>
            <button type="submit" name="register" class="btn btn-custom">Register</button>
        </form>
        <div class="text-muted">
            Already have an account? <a href="login.php" class="text-primary">Sign In</a>
        </div>
    </div>

    <?php if (!empty($error_message)) : ?>
        <p class="error"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <?php if (!empty($success_message)) : ?>
        <p class="success"><?php echo $success_message; ?></p>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>




