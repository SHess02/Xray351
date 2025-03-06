
<?php
	session_start();
	if(true){ // isset($_SESSION['username'])
		
		include '../includes/includes.php';

		$db = new Database();
		echo "<h1>Browsing Tab</h1>";

		// Fetch the 3 most recent jobs
		$query_jobs = "SELECT jobid, title FROM job ORDER BY opendate DESC LIMIT 3";
		$result_jobs = $db->query($query_jobs);

		// Fetch the 3 most recent companies
		$query_companies = "SELECT companyid, name FROM company ORDER BY name ASC LIMIT 3";
		$result_companies = $db->query($query_companies);

		// Fetch the 3 most recent events
		$query_events = "SELECT eventid, name FROM event ORDER BY datetime DESC LIMIT 3";
		$result_events = $db->query($query_events);

		echo "<div class='lists-container'>";

		// Jobs list
		echo "<div class='list'>";
		echo "<h3>Recent Jobs</h3>";
		if ($result_jobs->num_rows > 0) {
			echo "<ul>";
			while ($row = $result_jobs->fetch_assoc()) {
				echo "<li><a href=\"job.php?jobid=" . htmlspecialchars($row['jobid']) . "\">" . htmlspecialchars($row['title']) . "</a></li>";
			}
			echo "</ul>";
		} else {
			echo "<p>No recent jobs found.</p>";
		}
		echo "<a href='view_all.php?type=jobs' class='view-all-link'>View All Jobs</a>";
		echo "</div>";

		// Companies list
		echo "<div class='list'>";
		echo "<h3>Hiring Companies</h3>";
		if ($result_companies->num_rows > 0) {
			echo "<ul>";
			while ($row = $result_companies->fetch_assoc()) {
				echo "<li><a href=\"company.php?companyid=" . htmlspecialchars($row['companyid']) . "\">" . htmlspecialchars($row['name']) . "</a></li>";
			}
			echo "</ul>";
		} else {
			echo "<p>No hiring companies found.</p>";
		}
		echo "<a href='view_all.php?type=companies' class='view-all-link'>View All Companies</a>";
		echo "</div>";

		// Events list
		echo "<div class='list'>";
		echo "<h3>Recent Events</h3>";
		if ($result_events->num_rows > 0) {
			echo "<ul>";
			while ($row = $result_events->fetch_assoc()) {
				echo "<li><a href=\"event.php?eventid=" . htmlspecialchars($row['eventid']) . "\">" . htmlspecialchars($row['name']) . "</a></li>";
			}
			echo "</ul>";
		} else {
			echo "<p>No recent events found.</p>";
		}
		echo "<a href='view_all.php?type=events' class='view-all-link'>View All Events</a>";
		echo "</div>";

		echo "</div>"; // Close lists container

		$result_jobs->free();
		$result_companies->free();
		$result_events->free();

	}
	else {
		echo "User Not Logged in";
		echo "<form action='../SH_folder/login.php'>";
			echo "<input type='submit' value='Go to Login' />";
		echo "</form>";
	}
?>


