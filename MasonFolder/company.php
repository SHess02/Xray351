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

// Get company details
$companyid = $_GET['companyid'] ?? null;
if (!$companyid) {
    die("Company ID is required.");
}

$select_company = "SELECT * FROM company WHERE companyid = $companyid";
$company_result = $db->query($select_company);

if ($company_result->num_rows == 0) {
    die("Company not found.");
}
$company = $company_result->fetch_assoc();

// Check if the company is already favorited
$check_fav = "SELECT * FROM favorite_company WHERE companyid = $companyid AND listid = $listid";
$check_result = $db->query($check_fav);
$isFavorited = $check_result->num_rows > 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Company Details</title>
    <style>
        .company-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            background-color: #f9f9f9;
            position: relative;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .company-description {
            font-size: 16px;
            margin-bottom: 15px;
        }
        .active-jobs {
            margin-top: 20px;
        }
        .job-item {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .job-item:last-child {
            border-bottom: none;
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
    </style>
    <script>
        function toggleFavorite(companyid) {
            fetch('favorite_company.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'companyid=' + companyid
            })
            .then(response => response.text())
            .then(() => location.reload());
        }
    </script>
</head>
<body>

<div class="company-container">
    <button class="favorite-btn" onclick="toggleFavorite(<?php echo $companyid; ?>)">
        ★
    </button>
    <p class="company-name"><?php echo htmlspecialchars($company['name']); ?></p>
    <p class="company-description"><?php echo nl2br(htmlspecialchars($company['description'])); ?></p>
    <p>Active Listings: <?php echo htmlspecialchars($company['activelistings']); ?></p>

    <?php
    // Fetch active jobs from this company
    $query_jobs = "SELECT jobid, title, opendate, closedate 
                   FROM job 
                   WHERE alumniid = '$companyid' 
                   AND closedate >= CURDATE()";

    $result_jobs = $db->query($query_jobs);

    if ($result_jobs->num_rows > 0) {
        echo "<div class='active-jobs'>";
        echo "<h3>Active Job Postings</h3>";
        while ($job = $result_jobs->fetch_assoc()) {
            echo "<div class='job-item'>";
            echo "<p><a href='job.php?jobid=" . htmlspecialchars($job['jobid']) . "'>" . htmlspecialchars($job['title']) . "</a></p>";
            echo "<p>Open Date: " . htmlspecialchars($job['opendate']) . "</p>";
            echo "<p>Closing Date: " . htmlspecialchars($job['closedate']) . "</p>";
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "<p>No active job postings at this time.</p>";
    }
    ?>
	
	<button class="back-button" onclick="history.back()">Go Back</button>
	<?php
	
	$userid = intval($_SESSION['userid']); // Sanitize the session value as an integer
	$sql = "SELECT role FROM user WHERE userid = $userid";
	$result = $db->query($sql);
	
	if ($result && $result->num_rows > 0) {
		$row = $result->fetch_assoc();
		$role = $row['role'];
		if ($role === "alumni" || $role === "admin"){
			echo "<button class='back-button' onclick=\"window.location.href='companyedit.php?companyid=" . htmlspecialchars($company['companyid']) . "'\">Edit Company</button>";
			echo "<button class='back-button' onclick=\"window.location.href='deletecompany.php?companyid=" . htmlspecialchars($company['companyid']) . "'\">Delete Company</button>";
		}
	}
	?>
</div>

</body>
</html>
