<?php
session_start();
include '../SH_folder/db_connect_temp.php';
require '../EthanWork/mailer.php'; // Include the mailer for email verification

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim(strtolower($_POST['email']));
    $role = $_POST['role'];
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate email format (must be @cnu.edu)
    if (!preg_match("/^[a-zA-Z0-9._%+-]+@cnu\.edu$/", $email)) {
        echo "Invalid email. You must use a @cnu.edu email address.";
        exit();
    }

    // Validate input lengths
    if (
        strlen($name) > 0 && strlen($name) <= 45 &&
        strlen($email) > 0 && strlen($email) <= 45 &&
        strlen($confirm_password) >= 8 && strlen($confirm_password) <= 255
    ) {
        if ($password !== $confirm_password) {
            echo "Passwords do not match";
        } else {
            // Check if the email already exists
            $stmt = $conn->prepare("SELECT userid FROM user WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                echo "Email already exists";
            } else {
                $stmt->close();

                // Hash the password for security
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Generate a random verification token
                $verification_token = bin2hex(random_bytes(32));

                // Insert new user into the database
                $stmt = $conn->prepare("INSERT INTO user (email, role, name, password, email_verified, verification_token) VALUES (?, ?, ?, ?, 0, ?)");
                $stmt->bind_param("sssss", $email, $role, $name, $hashed_password, $verification_token);

                if ($stmt->execute()) {
                    // Send verification email
                    sendVerificationEmail($email, $verification_token);

                    // Redirect to success page
                    header("Location: registration_success.php");
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
    </style>
</head>

<body>
    <div class="registration-form">
        <div class="form-header">
            <h2>Register Your Account</h2>
        </div>
        <form method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">CNU Email Address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Role</label>
                <div class="d-flex">
                    <div class="form-check me-3">
                        <input type="radio" id="student" name="role" value="student" class="form-check-input" required>
                        <label for="student" class="form-check-label">Student</label>
                    </div>
					<div class="form-check me-3">
                        <input type="radio" id="faculty" name="role" value="faculty" class="form-check-input">
                        <label for="faculty" class="form-check-label">Faculty</label>
                    </div>
                    <div class="form-check">
                        <input type="radio" id="alumni" name="role" value="alumni" class="form-check-input">
                        <label for="alumni" class="form-check-label">Alumni</label>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Create Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Create a password" required>
            </div>

            <div class="mb-3">
                <label for="confirm-password" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirm-password" name="confirm_password" placeholder="Confirm your password" required>
            </div>

            <div class="btn-container">
                <button type="submit" name="register" class="btn btn-custom">Register</button>
            </div>
        </form>

        <div class="text-muted">
            Already have an account? <a href="login.php" class="text-primary">Sign In</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>