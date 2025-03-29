<?php
session_start();
include '../includes/session_check.php';
include '../includes/includes.php';
$db = new Database();

if (!isset($_SESSION['userid'])) {
    die("User not logged in.");
}

$userid = $_SESSION['userid'];
$jobid = $_POST['jobid'] ?? null;

if (!$jobid) {
    die("Job ID is required.");
}

// Ensure the user has a favorite list
$query = "INSERT INTO favorite_list (userid)  
          SELECT $userid FROM DUAL  
          WHERE NOT EXISTS (  
              SELECT 1 FROM favorite_list WHERE userid = $userid  
          )";
$db->query($query);

// Get listid
$select_fav_list = "SELECT listid FROM favorite_list WHERE userid = $userid";
$listid_result = $db->query($select_fav_list);
$listid_row = $listid_result->fetch_assoc();
$listid = $listid_row['listid'];

// Check if the job is already favorited
$check_fav = "SELECT * FROM favorite_job WHERE jobid = $jobid AND listid = $listid";
$check_result = $db->query($check_fav);

if ($check_result->num_rows > 0) {
    // If already favorited, remove it
    $delete_fav = "DELETE FROM favorite_job WHERE jobid = $jobid AND listid = $listid";
    $db->query($delete_fav);
} else {
    // If not favorited, add it
    $insert_fav = "INSERT INTO favorite_job (jobid, listid) VALUES ($jobid, $listid)";
    $db->query($insert_fav);
}
?>
