<?php

// Start the session
session_start();


require_once 'C:/xampp/htdocs/Xray351/includes/functional/database.php';
$conn = new Database();

if (isset($_POST['submit'])) {
    
    $usernameinput = $_POST['usernameinput'];
    $passwordinput = $_POST['passinput'];
	


    $checkQuery = "SELECT * FROM user WHERE email = '$usernameinput' AND password = '$passwordinput'";
    
    $result = $conn->query($checkQuery);

    if ($result->num_rows > 0) {

        $_SESSION['usernameinput'] = $usernameinput;


        header("Location: ../MasonFolder/browsingtab.php");
        exit();
    } else {
        echo "Username or password is incorrect<br>";
    }
}
?>


<!DOCTYPE html>
<html lang = "en">
    
    <head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title> AlumniConnect </title>
		<link rel="stylesheet" href="styles.css">

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

				.login {
					color: #3d3d29;
					font-style: italic;
					font-size: 20px;
					font-family: Helvetica;
					text-align: center;
					}
			</style>
    </head>
    <body>
        
        <h1 class="username">Alumni Connect</h1>
		
		<div class ="center-container">
			<img src="cnu_logo.jpg" class ="center-image" alt="none">
		</div>
		
		

        <form action="" method="post">
            <p class="login">
                Username:<br>
                <input type="text" name="usernameinput" required> <br><br>

                Password:<br>
                <input type="password" name="passinput" required> <br><br>
                
				<input type="submit" name="submit" value="Login"><br><br>
				<a href="register.php"><button type="button">Register</button></a>
            </p>
			<br><a href="C:\xampp\htdocs\Xray351\EthanWork\forgot_password.php">Forgot password?</a>
		
        
		
        </form>
    </body>
</html>
