<?php
// Start the session
session_start();

// Create connection

require_once '../database.php';
$dbname = new Database();
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['register'])) {
    // Get the user input from the form
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $city = $_POST['city'];  

    // Validate that the passwords match
    if ($password !== $confirm_password) {
        $error_message = "Passwords do not match.";
    } else {
        // Validate that the username and email are unique
        $checkQuery = "SELECT * FROM user WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // User already exists, handle the error
            $error_message = "Username or Email already taken.";
        } else {


            // Insert the new user into the database
            $insertQuery = "INSERT INTO user (username, email, password, city) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("ssss", $username, $email, $password, $city);
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
    <title>Registration Page</title>
    <style>
        h1 { text-align: center; }
        .error { color: red; }
        form { text-align: center; }
        .success { color: green; }
    </style>
</head>
<body>

    <h1>Registration Form</h1>

    <!-- Registration Form -->
    <form method="POST" action="">
        <label for="email">Email: </label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="username">Username: </label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="city">City: </label>
        <input type="text" id="city" name="city" required><br><br> 

        <label for="password">Password: </label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="confirm_password">Confirm Password: </label>
        <input type="password" id="confirm_password" name="confirm_password" required><br><br>

        <input type="submit" name="register" value="Register"><br><br>
        <a href="login.php">Back to login</a>
    </form>

    <?php if (!empty($error_message)) : ?>
        <p class="error"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <?php if (!empty($success_message)) : ?>
        <p class="success"><?php echo $success_message; ?></p>
    <?php endif; ?>

</body>
</html>
