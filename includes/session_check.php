<!-- Mason DeGraef -->
<?php
$do_session_check = true;

if ($do_session_check && !isset($_SESSION['userid'])) {
    header("Location: ../SH_folder/login.php");
    exit();
}

?>