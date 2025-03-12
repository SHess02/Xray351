<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim(strtolower($_POST['email']));
    $security_a1 = trim(strtolower($_POST['security_a1']));
    $security_a2 = trim(strtolower($_POST['security_a2']));

    if (!empty($email) && !empty($security_a1) && !empty($security_a2)) {
        $stmt = $conn->prepare("SELECT user_id FROM user WHERE email = ? AND security_a1 = ? AND security_a2 = ?");
        $stmt->bind_param("sss", $email, $security_a1, $security_a2);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id);
            $stmt->fetch();

            $token = bin2hex(random_bytes(32));

            $_SESSION['reset_token'] = $token;
            $_SESSION['reset_user_id'] = $user_id;

            header("Location: reset_password.php?token=$token");
            exit();
        } else {
            echo "Invalid email or security answers. Please try again.";
        }

        $stmt->close();
    } else {
        echo "All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">
    <title>Forgot Password : QuickServe Reservations</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <h2>Forgot Password</h2><br>
    <form method="post">
        <label for="email"><b>Email:</b></label><br>
        <input type="email" name="email" required><br><br>

        <label for="security_a1"><b>Security Question #1:<br>What was the year, make, and model of your first car?</b></label><br>
        <input type="text" name="security_a1" required><br><br>

        <label for="security_a2"><b>Security Question #2:<br>What was your childhood nickname?</b></label><br>
        <input type="text" name="security_a2" required><br><br>

        <button class="btn btn-primary">Verify</button>
    </form>
</body>
</html>