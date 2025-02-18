<?php
// Start the session to manage session data
session_start();

// Destroy all session data to log the user out
session_destroy();

// Optionally, clear the session cookie by setting its expiration to the past
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
}

// Redirect to a logout confirmation page
header("Location: logout_message.php");
exit();
?>
