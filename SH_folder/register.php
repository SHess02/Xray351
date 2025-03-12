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

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $graduationyear = $_POST['graduationyear'];
    $major = $_POST['major'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        $checkQuery = "SELECT * FROM student WHERE name = ? OR email = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("ss", $name, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = "Email already taken.";
        } else {
            $insertQuery = "INSERT INTO student (name, email, password, graduationyear, major) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("sssss", $name, $email, $password, $graduationyear, $major);
            if ($stmt->execute()) {
                // Registration successful
                $success_message = "Registration successful!";
            } else {
                $error_message = "Error: " . $stmt->error;
            }
        }
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
                <label for="name" class="form-label">Username</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="mb-3">
                <label for="graduationyear" class="form-label">Graduation Year</label>
                <input type="text" class="form-control" id="graduationyear" name="graduationyear" placeholder="Enter your Graduation Year" required>
            </div>
            <div class="mb-3">
                <label for="major" class="form-label">Major</label>
                <input type="text" class="form-control" id="major" name="major" placeholder="Enter your Major" required>
            </div>
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




