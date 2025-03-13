<?php
$conn = mysqli_connect('localhost', 'root', '', 'alumniconnectdb');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>