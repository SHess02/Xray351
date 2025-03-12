<?php
include '../includes/includes.php';

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
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">
    <title>User Registration | AlumniConnect</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <h2>User Registration</h2><br>
    <form action="admin_register.php" method="post">
        <label><b>Full Name:</b></label><br>
        <input type="text" name="name" required><br><br>

        <label><b>CNU Email:</b></label><br>
        <input type="email" name="email" required><br><br>

        <label><b>Role:</b></label><br>
        <select name="role" required>
            <option value="faculty">Faculty</option>
            <option value="admin">Administrator</option>
        </select><br><br>

        <label><b>Password:</b></label><br>
        <input type="password" name="password" required><br><br>

        <label><b>Confirm Password:</b></label><br>
        <input type="password" name="confirm_password" required><br><br>

        <label><b>Security Question #1:</b><br>What was the year, make, and model of your first car?</label><br>
        <input type="text" name="security_a1" required><br><br>

        <label><b>Security Question #2:</b><br>What was your childhood nickname?</label><br>
        <input type="text" name="security_a2" required><br><br>

        <button class="btn btn-primary">Register</button>
    </form>
</body>
</html>