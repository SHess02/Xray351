<?php
	session_start();
	include '../includes/session_check.php';
	include '../includes/includes.php';
	$db = new Database();
	
	// Get listid
	$select_fav_list = 'SELECT listid FROM favorite_list WHERE studentid = '.$_SESSION['userid'].'';
	$listid_result = $db->query($select_fav_list);

	if ($listid_result->num_rows > 0) {
		$listid_row = $listid_result->fetch_assoc();
		$listid = $listid_row['listid'];

		// Fetch favorite companies with details
		$select_fav_company = "
			SELECT c.companyid, c.name, c.description, c.activelistings 
			FROM favorite_company fc
			JOIN company c ON fc.companyid = c.companyid
			WHERE fc.listid = $listid";
		$fav_company_result = $db->query($select_fav_company);

		// Fetch favorite jobs with details
		$select_fav_job = "
			SELECT j.jobid, j.title, j.description, j.opendate, j.closedate, j.contactemail 
			FROM favorite_job fj
			JOIN job j ON fj.jobid = j.jobid
			WHERE fj.listid = $listid";
		$fav_job_result = $db->query($select_fav_job);

		// Display favorite companies
		echo "<h3>Favorite Companies</h3>";
		echo "<table border='1'>";
		echo "<tr><th>Name</th><th>Description</th><th>Active Listings</th></tr>";
		while ($company_row = $fav_company_result->fetch_assoc()) {
			echo "<tr>
					<td>{$company_row['name']}</td>
					<td>{$company_row['description']}</td>
					<td>{$company_row['activelistings']}</td>
				  </tr>";
		}
		echo "</table>";

		// Display favorite jobs
		echo "<h3>Favorite Jobs</h3>";
		echo "<table border='1'>";
		echo "<tr><th>Title</th><th>Description</th><th>Open Date</th><th>Close Date</th><th>Contact Email</th></tr>";
		while ($job_row = $fav_job_result->fetch_assoc()) {
			echo "<tr>
					<td>{$job_row['title']}</td>
					<td>{$job_row['description']}</td>
					<td>{$job_row['opendate']}</td>
					<td>{$job_row['closedate']}</td>
					<td>{$job_row['contactemail']}</td>
				  </tr>";
		}
		echo "</table>";
	} else {
		echo "No favorite list found for this student.";
	}



?>