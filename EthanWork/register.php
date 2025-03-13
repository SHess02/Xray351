<?php
include 'db_connect_temp.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim(strtolower($_POST['email']));
    $role = trim($_POST['role']);
	$password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $security_a1 = trim(strtolower($_POST['security_a1']));
    $security_a2 = trim(strtolower($_POST['security_a2']));

    if (!preg_match("/^[a-zA-Z0-9._%+-]+@cnu\.edu$/", $email)) {
        echo "Invalid email. You must use a @cnu.edu email address.";
        exit();
    }

    if (strlen($name) > 0 && strlen($name) <= 45 &&
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
                    header("Location: login.php");
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
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">
    <title>User Registration | AlumniConnect</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
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
                <label for="name" class="form-label">Username</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Create a password" required>
            </div>
            <div class="mb-3">
                <label for="confirm-password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirm-password" name="confirm_password" placeholder="Confirm your password" required>           
			</div>
            <label><b>Security Question #1:</b><br>What was the year, make, and model of your first car?</label><br>
			<input type="text" name="security_a1" required><br><br>

			<label><b>Security Question #2:</b><br>What was your childhood nickname?</label><br>
			<input type="text" name="security_a2" required><br><br>
			
			<button type="submit" name="register" class="btn btn-custom">Register</button>
        
		</form>
        <div class="text-muted">
            Already have an account? <a href="login.php" class="text-primary">Sign In</a>
        </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	</div>
</body>
</html>