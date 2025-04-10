<?php
session_start();
include '../SH_folder/db_connect_temp.php';

if (!isset($_SESSION['userid'])) {
    header("Location: ../login.php");
    exit;
}

$session_userid = intval($_SESSION['userid']);
$edit_userid = isset($_GET['userid']) ? intval($_GET['userid']) : 0;
if ($edit_userid === 0) {
    die("No user ID specified.");
}

// Fetch session user's role
$stmt = $conn->prepare("SELECT role FROM user WHERE userid = ?");
$stmt->bind_param("i", $session_userid);
$stmt->execute();
$result = $stmt->get_result();
$session_user = $result->fetch_assoc();
$stmt->close();

if (!$session_user) {
    die("Logged in user not found.");
}

$session_role = $session_user['role'];

// Kick back if not admin and not editing own profile
if ($session_userid !== $edit_userid && $session_role !== 'admin') {
    echo "<script>history.back();</script>";
    exit;
}

// Fetch user being edited
$stmt = $conn->prepare("SELECT role, name, email, major, graduationyear, aboutme FROM user WHERE userid = ?");
$stmt->bind_param("i", $edit_userid);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    die("User not found.");
}

$role = $user['role'];
$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Double-check ownership/admin again on POST for security
    if ($session_userid !== $edit_userid && $session_role !== 'admin') {
        echo "<script>history.back();</script>";
        exit;
    }

    // Sanitize input
    $name = trim($_POST['name']);
    $email = trim(strtolower($_POST['email']));
    $aboutme = trim($_POST['aboutme'] ?? '');
    $major = trim($_POST['major'] ?? '');
    $graduationyear = trim($_POST['graduationyear'] ?? null);

    $query = "UPDATE user SET name = ?, email = ?, aboutme = ?";
    $params = [$name, $email, $aboutme];
    $types = "sss";

    if ($role == 'student' || $role == 'alumni' || $role == 'faculty') {
        $query .= ", major = ?";
        $params[] = $major;
        $types .= "s";
    }

    if ($role == 'student') {
        $query .= ", graduationyear = ?";
        $params[] = $graduationyear;
        $types .= "s";
    }

    $query .= " WHERE userid = ?";
    $params[] = $edit_userid;
    $types .= "i";

    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        $message = "Profile updated successfully! Please refresh the page to view changes.";
    } else {
        $message = "Error updating profile: " . $conn->error;
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">
    <title>Profile : AlumniConnect</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Manage Profile</h2><br>

        <?php if (!empty($message)): ?>
            <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <form action="profile_edit.php?userid=<?= $edit_userid ?>" method="post">
            <div class="mb-3">
                <label><b>Role</b></label>
                <input type="text" value="<?= htmlspecialchars($role) ?>" disabled class="form-control">
            </div>

            <div class="mb-3">
                <label><b>Name</b></label>
                <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required class="form-control">
            </div>

            <div class="mb-3">
                <label><b>Email</b></label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required class="form-control">
            </div>

            <?php if ($role == 'student' || $role == 'alumni' || $role == 'faculty'): ?>
                <div class="mb-3">
                    <label><b>Major</b></label>
                    <input type="text" name="major" value="<?= htmlspecialchars($user['major'] ?? '') ?>" class="form-control">
                </div>
            <?php endif; ?>

            <?php if ($role == 'student'): ?>
                <div class="mb-3">
                    <label><b>Graduation Year</b></label>
                    <input type="text" name="graduationyear" value="<?= htmlspecialchars($user['graduationyear'] ?? '') ?>" class="form-control">
                </div>
            <?php elseif ($role == 'alumni'): ?>
                <div class="mb-3">
                    <label><b>Graduation Year</b></label>
                    <input type="text" value="<?= htmlspecialchars($user['graduationyear'] ?? '') ?>" disabled class="form-control">
                </div>
            <?php endif; ?>

            <div class="mb-3">
                <label><b>About Me</b></label>
                <textarea name="aboutme" class="form-control"><?= htmlspecialchars($user['aboutme'] ?? '') ?></textarea>
            </div>

            <button class="btn btn-primary">Update Profile</button>
        </form>

        <br>
        <a href='../MasonFolder/browsingtab.php' class="btn btn-secondary">Home</a>
    </div>
</body>
</html>
