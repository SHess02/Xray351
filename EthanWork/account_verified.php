<?php
require '../SH_folder/db_connect_temp.php'; // Include your database connection

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

        echo "Email successfully verified! You can now <a href='../SH_folder/login.php'>log in</a>.";
    } else {
        echo "Invalid or expired token.";
    }
} else {
    echo "No token provided.";
}
?>