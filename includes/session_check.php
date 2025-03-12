<?php
$do_session_check = false;

if ($do_session_check && !isset($_SESSION['user_id'])) {
    header("Location: ../SH_folder/login.php");
    exit();
}

?>