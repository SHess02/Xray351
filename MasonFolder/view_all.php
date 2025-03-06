
<?php
	if( isset($_GET['type'])){
		include '../includes/includes.php';
		$db = new Database();
		
		switch ($_GET['type']){
			
			case 'jobs':
				$select_job = 'select * from job'; // Get jobs from db, company, event

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
			
				break;
				
			case 'companies':
				$select_company = 'select * from company';

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
				break;
				
			case 'events':
				$select_event = 'select * from event';
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
				break;
		}
	}
?>
