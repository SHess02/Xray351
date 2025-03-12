<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// Fetch user details including role
$stmt = $conn->prepare("SELECT role, name, email, major, graduationyear, aboutme FROM user WHERE userid = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

$role = $user['role'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim(strtolower($_POST['email']));
    $aboutme = trim($_POST['aboutme'] ?? '');
    $major = trim($_POST['major'] ?? '');
    $graduationyear = trim($_POST['graduationyear'] ?? null);

    // Build the SQL query dynamically based on role
    $query = "UPDATE user SET name = ?, email = ?, aboutme = ?";
    $params = [$name, $email, $aboutme];
    $types = "sss";

    if ($role == 'student' || $role == 'alumni') {
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
    $params[] = $user_id;
    $types .= "i";

    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        $message = "Profile updated successfully!";
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

        <form action="profile.php" method="post">
            <div class="mb-3">
                <label><b>Role</b></label><br>
                <input type="text" value="<?= htmlspecialchars($user['role']) ?>" disabled class="form-control"><br>
            </div>

            <div class="mb-3">
                <label><b>Name</b></label><br>
                <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required class="form-control"><br>
            </div>

            <div class="mb-3">
                <label><b>Email</b></label><br>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required class="form-control"><br>
            </div>

            <?php if ($role == 'student' || $role == 'alumni'): ?>
                <div class="mb-3">
                    <label><b>Major</b></label><br>
                    <input type="text" name="major" value="<?= htmlspecialchars($user['major'] ?? '') ?>" class="form-control"><br>
                </div>
            <?php endif; ?>

            <?php if ($role == 'student'): ?>
                <div class="mb-3">
                    <label><b>Graduation Year</b></label><br>
                    <input type="text" name="graduationyear" value="<?= htmlspecialchars($user['graduationyear'] ?? '') ?>" class="form-control"><br>
                </div>
            <?php elseif ($role == 'alumni'): ?>
                <div class="mb-3">
                    <label><b>Graduation Year</b></label><br>
                    <input type="text" value="<?= htmlspecialchars($user['graduationyear'] ?? '') ?>" disabled class="form-control"><br>
                </div>
            <?php endif; ?>

            <div class="mb-3">
                <label><b>About Me</b></label><br>
                <textarea name="aboutme" class="form-control"><?= htmlspecialchars($user['aboutme'] ?? '') ?></textarea><br>
            </div>

            <button class="btn btn-primary">Update Profile</button>
        </form>

        <br>
        <a href='home.php' class="btn btn-secondary">Home</a>
    </div>
</body>
</html>