<?php
require '../SH_folder/db_connect_temp.php'; // Include your database connection

$message = "";
$link = "../SH_folder/login.php";
$linkText = "Click here to login";

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Check if the token exists in the database
    $stmt = $conn->prepare("SELECT userid FROM user WHERE verification_token = ?");
    $stmt->bind_param("s", $token); // Bind token as a string
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        // Update the user to mark email as verified and remove the token
        $stmt = $conn->prepare("UPDATE user SET email_verified = 1, verification_token = NULL WHERE userid = ?");
        $stmt->bind_param("i", $user['userid']);
        $stmt->execute();

        $message = "Your email has been successfully verified!";
    } else {
        $message = "Invalid or expired verification token.";
        $linkText = "Return to login";
    }
} else {
    $message = "No verification token provided.";
    $linkText = "Return to login";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Account Verified</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: #fff;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }
        .container {
            max-width: 600px;
            padding: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo $message; ?></h1>
        <a class="btn btn-primary" role="button" href="<?php echo $link; ?>"><?php echo $linkText; ?></a>
    </div>
</body>
</html>