<?php
include 'db_connect_temp.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim(strtolower($_POST['email']));
    $role = $_POST['role'];
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $security_a1 = trim(strtolower($_POST['security_a1']));
    $security_a2 = trim(strtolower($_POST['security_a2']));

    if (!preg_match("/^[a-zA-Z0-9._%+-]+@cnu\.edu$/", $email)) {
        echo "Invalid email. You must use a @cnu.edu email address.";
        exit();
    }

    if (!in_array($role, ['faculty', 'admin'])) {
        echo "Invalid role selection.";
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration | AlumniConnect</title>
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

    <div class="registration-form">
        <div class="text-center">
            <h2>Admin Registration</h2>
        </div>

        <form action="admin_register.php" method="post">
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">CNU Email Address</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Role:</label><br>
                <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" id="faculty" name="role" value="faculty" required>
                    <label class="form-check-label" for="faculty">Faculty</label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="radio" class="form-check-input" id="admin" name="role" value="admin" required>
                    <label class="form-check-label" for="admin">Administrator</label>
                </div>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Create Password:</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
            </div>

            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password:</label>
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Enter password" required>
            </div>

            <div class="mb-3">
                <label for="security_a1" class="form-label">Security Question #1:<br>What was the year, make, and model of your first car?</label>
                <input type="text" class="form-control" id="security_a1" name="security_a1" placeholder="Enter answer" required>
            </div>

            <div class="mb-3">
                <label for="security_a2" class="form-label">Security Question #2:<br>What was your childhood nickname?</label>
                <input type="text" class="form-control" id="security_a2" name="security_a2" placeholder="Enter answer" required>
            </div>

            <div class="btn-container">
                <button type="submit" class="btn btn-custom">Register</button>
            </div>
        </form>
    </div>

</body>
</html>