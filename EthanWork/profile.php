<?php
// Ethan Belote
session_start();
include '../includes/session_check.php';
include '../includes/includes.php';
$db = new Database();

$userid = $_GET['userid'] ?? null;
if (!$userid) {
    die("User ID is required.");
}

$userid = intval($userid); // Sanitize input
$select_user = "SELECT name, email, major, aboutme FROM user WHERE userid = $userid";
$user_result = $db->query($select_user);

if ($user_result->num_rows == 0) {
    die("User not found.");
}
$user = $user_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    <style>
        .profile-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            background-color: #f9f9f9;
        }
        .profile-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .profile-info {
            font-size: 16px;
            margin-bottom: 10px;
        }
        .back-button {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="profile-container">
    <p class="profile-title"><?php echo htmlspecialchars($user['name']); ?></p>
    <p class="profile-info"><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
    <p class="profile-info"><strong>Major:</strong> <?php echo htmlspecialchars($user['major']); ?></p>
    <p class="profile-info"><strong>About Me:</strong><br><?php echo nl2br(htmlspecialchars($user['aboutme'])); ?></p>
    
	<?php
	$session_userid = intval($_SESSION['userid']);
	$profile_userid = intval($_GET['userid']); // assuming this is already validated

	// Fetch user role
	$role_query = "SELECT role FROM user WHERE userid = $session_userid";
	$role_result = $db->query($role_query);
	$role = "";

	if ($role_result && $role_result->num_rows > 0) {
		$row = $role_result->fetch_assoc();
		$role = $row['role'];
	}

	// Show Edit Profile button if user is admin or viewing their own profile
	if ($role === "admin" || $session_userid === $profile_userid) {
		echo "<button class='back-button' onclick=\"window.location.href='/Xray351/MasonFolder/profile_edit.php?userid=$profile_userid'\">Edit Profile</button>";
	}
	
	if ($session_userid !== $profile_userid) {
    echo "<button class='back-button' onclick=\"window.location.href='/Xray351/SH_Folder/inbox.php'\">Message</button>";
	}

	?>

</div>

</body>
</html>
