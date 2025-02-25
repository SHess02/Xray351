<?php

	if(true){ // isset($_SESSION['username'])
		require_once '../database.php';

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
	}

?>


<style>
    body {
        font-family: Arial, sans-serif;
    }

    .lists-container {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        margin-top: 20px;
    }

    .list {
        flex: 1;
        padding: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        background-color: #f9f9f9;
        text-align: center;
    }

    h3 {
        text-align: center;
        font-size: 1.2em;
        font-weight: bold;
        color: #333;
    }

    ul {
        list-style-type: none;
        padding: 0;
    }

    ul li {
        margin: 5px 0;
    }

    ul li a {
        text-decoration: none;
        color: #007bff;
        font-weight: bold;
        font-size: 1.1em;
    }

    ul li a:hover {
        text-decoration: underline;
    }

    /* Table Styling */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f8f9fa;
        border-radius: 8px;
        overflow: hidden;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 12px;
        text-align: center;
    }

    th {
        background-color: #007bff;
        color: white;
        font-weight: bold;
        cursor: pointer;
    }

    td {
        font-size: 1.1em;
        color: #333;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tr:hover {
        background-color: #e2e6ea;
    }
	.view-all-link {
        display: block;
        margin-top: 15px;
        text-align: center;
        font-weight: bold;
        color: #007bff;
        text-decoration: none;
    }

    .view-all-link:hover {
        text-decoration: underline;
    }
</style>

