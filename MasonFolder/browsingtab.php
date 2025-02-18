<?php
	
	
	if(true){ // isset($_SESSION['username'])
		require_once '../database.php';

		echo "<h1>Browsing Tab</h1>";
	
		// Search bar
		// Query TITLE like, DESCRIPTION like, rather than equal to.
	
	
		echo "<h3>Recent Jobs</h3>";
		echo "3-5 most recent job postings";
		// Links to job.php 
		echo "<h3>Upcoming Events</h3>";
		echo "3-4 soonest events";
		// Links to event.php 
		echo "<h3>Hiring Companies</h3>";
		echo "3-5 companies hiring";
		// Link to company.php 
		echo "<h4>View All Jobs</h4>";
		// Link to browsingjobs
		echo "<h4>View All Events</h4>";
		// Link to browsing events
		echo "<h4>View All Companies</h4>";
		// Link to browsing companies
	
		$db = new Database();

		$select_job = 'select * from job'; // Get jobs from db, company, event

		switch (@$_GET['order']) {
			case 'job':
			case 'company':
			case 'event': $select .= ' order by '.$_GET['order'];
		}

		$result_job = $db->query( $select_job );
		$rows_job   = $result_job->num_rows;

		echo "<table class=\"Browsing Tab\">\n";
		echo "<tr>\n";
		echo "<th></th>";
		echo "<th><a href=\"browsingtab.php?order=title\" /> Title </a></th>";
		echo "<th><a href=\"browsingtab.php?order=description\" /> Description </a></th>";
		echo "<th><a href=\"browsingtab.php?order=opendate\" /> Date </a></th>";
		echo "<tr>\n";
		if ($rows_job == 0) {
			echo "<tr>\n";
			echo "<td colspan=\"3\">Nothing to Display</td>";
			echo "</tr>\n";
		}
		else {
			for ($i=0; $i<$rows_job; $i++) {
				$row = $result_job->fetch_assoc();
				echo "<tr class=\"highlight\">";
				echo "<td>".($i+1)."</td>";
				echo "<td><a href=\"job.php?title=".$row['jobid']."\" />".$row['title']."</a></td>";
				echo"<td>".$row['description']."</td>";
				echo"<td>".$row['opendate']."</td>";
				echo "</tr>\n";
			}
		}
		echo "</table>\n";

		$result_job->free();


		$select_company = 'select * from company';

		switch (@$_GET['order']) {
			case 'job':
			case 'company':
			case 'event': $select .= ' order by '.$_GET['order'];
		}

		$result_company = $db->query( $select_company );
		$rows_company   = $result_company->num_rows;


		echo "<table class=\"Browsing Tab\">\n";
		echo "<tr>\n";
		echo "<th></th>";
		echo "<th><a href=\"browsingtab.php?order=name\" /> Comapny Name </a></th>";
		echo "<th><a href=\"browsingtab.php?order=description\" /> Description </a></th>";
		echo "<th><a href=\"browsingtab.php?order=activelistings\" /> Open Listings </a></th>";
		echo "<tr>\n";
		if ($rows_company == 0) {
			echo "<tr>\n";
			echo "<td colspan=\"3\">Nothing to Display</td>";
			echo "</tr>\n";
		}
		else {
			for ($i=0; $i<$rows_company; $i++) {
				$row = $result_company->fetch_assoc();
				echo "<tr class=\"highlight\">";
				echo "<td>".($i+1)."</td>";
				echo "<td><a href=\"company.php?name=".$row['companyid']."\" />".$row['name']."</a></td>";
				echo"<td>".$row['description']."</td>";
				echo"<td>".$row['activelistings']."</td>";
				echo "</tr>\n";
			}
		}
		echo "</table>\n";
		
		$result_company->free();

		$select_event = 'select * from event';

		switch (@$_GET['order']) {
			case 'job':
			case 'company':
			case 'event': $select .= ' order by '.$_GET['order'];
		}

		$result_event = $db->query( $select_event );
		$rows_event   = $result_event->num_rows;

		echo "<table class=\"Browsing Tab\">\n";
		echo "<tr>\n";
		echo "<th></th>";
		echo "<th><a href=\"browsingtab.php?order=name\" /> Event Name </a></th>";
		echo "<th><a href=\"browsingtab.php?order=location\" /> Location </a></th>";
		echo "<th><a href=\"browsingtab.php?order=datetime\" /> Date </a></th>";
		echo "<tr>\n";
		if ($rows_event == 0) {
			echo "<tr>\n";
			echo "<td colspan=\"3\">Nothing to Display</td>";
			echo "</tr>\n";
		}
		else {
			for ($i=0; $i<$rows_event; $i++) {
				$row = $result_event->fetch_assoc();
				echo "<tr class=\"highlight\">";
				echo "<td>".($i+1)."</td>";
				echo "<td><a href=\"event.php?name=".$row['eventid']."\" />".$row['name']."</a></td>";
				echo"<td>".$row['location']."</td>";
				echo"<td>".$row['datetime']."</td>";
				echo "</tr>\n";
			}
		}
		echo "</table>\n";
		$result_event->free();

		
	}
	else {
		
		echo "User Not Logged in";
	}

?>
