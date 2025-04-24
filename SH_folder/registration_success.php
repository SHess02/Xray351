<?php
//Ethan Belote
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful</title>
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
        .success-container {
            background: #ffffff;
            color: #000;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        .btn-custom {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: #fff;
            border: none;
            transition: background 0.3s ease;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="success-container">
        <h2>Registration Successful!</h2>
        <p>We have sent you a verification email. Please check your inbox and verify your email before logging in.</p>
        <a href="login.php" class="btn btn-custom">Go to Login</a>
    </div>
</body>
</html>