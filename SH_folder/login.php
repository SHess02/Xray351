<?php

// Start the session
session_start();

$servername = "localhost"; 
$username = "root";
$password = "";
$dbname = "alumniconnect";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST['submit'])) {
    
    $usernameinput = $_POST['usernameinput'];
    $passwordinput = $_POST['passinput'];
	

    // Corrected query to check both username and password
    $checkQuery = "SELECT * FROM student WHERE email = '$usernameinput' AND password = '$passwordinput'";
    
    // Query the database
    $result = $conn->query($checkQuery);

    // Check if any row was returned (user found)
    if ($result->num_rows > 0) {
        // Set the session variable to keep the user logged in
        $_SESSION['usernameinput'] = $usernameinput;

        // Redirect to dashboard.php after successful login
        header("Location: dashboard.php");
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
			<style>
				body {
					background-color: #b8b894;
					background-size: cover;
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
					color: darkblue;
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
                
                <input type="submit" name="submit" value="Login"><br>
				<br><input type= "submit" name="submit" value ="Registar">
            </p>
		
        
		
        </form>
    </body>
</html>
