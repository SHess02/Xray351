<!-- Mason DeGraef -->
<?php
session_start();
include '../includes/session_check.php';
include '../includes/includes.php';
$db = new Database();

// Ensure the user has a favorite list
$userid = $_SESSION['userid'];

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

// Get job details
$jobid = $_GET['jobid'] ?? null;
if (!$jobid) {
    die("Job ID is required.");
}

$select_job = "SELECT * FROM job WHERE jobid = $jobid";
$job_result = $db->query($select_job);

if ($job_result->num_rows == 0) {
    die("Job not found.");
}
$job = $job_result->fetch_assoc();

// Check if the job is already favorited
$check_fav = "SELECT * FROM favorite_job WHERE jobid = $jobid AND listid = $listid";
$check_result = $db->query($check_fav);
$isFavorited = $check_result->num_rows > 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Job Details</title>
    <style>
        .job-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            background-color: #f9f9f9;
            position: relative;
        }
        .job-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .job-description {
            font-size: 16px;
            margin-bottom: 15px;
        }
        .job-date {
            font-size: 14px;
            color: #666;
        }
        .favorite-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            border: none;
            background: none;
            cursor: pointer;
            font-size: 24px;
            color: <?php echo $isFavorited ? 'gold' : 'gray'; ?>;
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
    <script>
        function toggleFavorite(jobid) {
            fetch('favorite_job.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'jobid=' + jobid
            })
            .then(response => response.text())
            .then(() => location.reload());
        }
    </script>
</head>
<body>

<div class="job-container">
    <button class="favorite-btn" onclick="toggleFavorite(<?php echo $jobid; ?>)">
        ★
    </button>
    <p class="job-title"><?php echo htmlspecialchars($job['title']); ?></p>
    <p class="job-description"><?php echo nl2br(htmlspecialchars($job['description'])); ?></p>
    <p class="job-date">Posted on: <?php echo htmlspecialchars($job['opendate']); ?></p>
    
	<button class="back-button" onclick="history.back()">Go Back</button>
	<?php
	
	function isJobCreator($db, $userid, $jobid) {
		$userid = intval($userid);
		$jobid = intval($jobid);

		$query = "SELECT alumniid FROM job WHERE jobid = $jobid";
		$result = $db->query($query);

		if ($result && $result->num_rows > 0) {
			$row = $result->fetch_assoc();
			return $row['alumniid'] == $userid;
		}

		return false;
	}
	
	$userid = intval($_SESSION['userid']); // Sanitize the session value as an integer
	$sql = "SELECT role FROM user WHERE userid = $userid";
	$result = $db->query($sql);
	
	if ($result && $result->num_rows > 0) {
		$row = $result->fetch_assoc();
		$role = $row['role'];
		if ($role === "alumni" || $role === "admin") {
			if ($role === "admin" || isJobCreator($db, $_SESSION['userid'], $job['jobid'])) {
				echo "<button class='back-button' onclick=\"window.location.href='jobedit.php?jobid=" . htmlspecialchars($job['jobid']) . "'\">Edit Job</button>";
				echo "<button class='back-button' onclick=\"window.location.href='deletejob.php?jobid=" . htmlspecialchars($job['jobid']) . "'\">Delete Job</button>";
			}
		}
	}
	?>
</div>

</body>
</html>
