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
            header("Location: home.php");
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
<html>
<head>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">
	<title>User Login : QuickServe Reservations</title>
	<link rel="stylesheet" type="text/css" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <h2>Login</h2><br>
    <form action="login.php" method="post">
        <label><b>Email:</b></label><br>
        <input type="email" name="email" required><br>
        <br>
        <label><b>Password:</b></label><br>
        <input type="password" name="password" required><br>
        <br>
        <button class="btn btn-primary">Login</button>
    </form>
	<br><a href="forgot_password.php">Forgot password?</a>
	<br><a href="registration.php">New user?</a>
</body>
</html>