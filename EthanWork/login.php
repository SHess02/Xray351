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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
		<style>
				body {
					background: linear-gradient(135deg, #6a11cb, #2575fc);
					color: #fff;
				
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

				header {
					text-align: center;
					}

				.center-container {
					display: flex;
					justify-content: center;
					align-items: center; 
					}

				.center-image {
					max-width: 60%; 
					height: auto;
					}

				.username {
					font-weight: bold;
					font-size: 60px;
					color: 	#c2c2a3;
					text-align: center;
					}
				.btn-custom {
					background: linear-gradient(135deg, #6a11cb, #2575fc);
					color: #fff;
					border: none;
					transition: background 0.3s ease;

				.login {
					color: #3d3d29;
					font-style: italic;
					font-size: 20px;
					font-family: Helvetica;
					text-align: center;
					}
				.h2 {
					text-align: center;
				}
		</style>
</head>
<body>
    <h1 class="username">Alumni Connect</h1>
		
	<div class ="center-container">
		<img src="cnu_logo.jpg" class ="center-image" alt="none">
	</div> <br>

	<div>
		<form action="login.php" method="post">

			<p class = "login">

			<label><b>Email:</b></label><br>
			<input type="email" name="email" required><br>
			<br>
			<label><b>Password:</b></label><br>
			<input type="password" name="password" required><br>
			<br>
			<button class="btn btn-primary">Login</button>
			</p>

		</form>

	</div>
		<br><a href="forgot_password.php" class="btn btn-custom">Forgot password?</a> <br>
		<br><a href="register.php" class="btn btn-custom">New user?</a>

</body>
</html>