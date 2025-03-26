
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
						echo "<td><a href=\"job.php?jobid=".$row['jobid']."\" />".$row['title']."</a></td>";
						echo"<td>".$row['description']."</td>";
						echo"<td>".$row['opendate']."</td>";
						echo "</tr>\n";
					}
				}
				echo "</table>\n";
				// Add Job Tab	
				echo "<br>";
				echo "<button onclick=\"window.location.href='addjob.php'\" class='btn-style'>Add Job</button>";
				echo "</div>";
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
						echo "<td><a href=\"company.php?companyid=".$row['companyid']."\" />".$row['name']."</a></td>";
						echo"<td>".$row['description']."</td>";
						echo"<td>".$row['activelistings']."</td>";
						echo "</tr>\n";
					}
				}
				echo "</table>\n";
				
				$result_company->free();
				// Add Company Tab	
				echo "<br>";
				echo "<button onclick=\"window.location.href='addcompany.php'\" class='btn-style'>Add Company</button>";
				echo "</div>";
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
						echo "<td><a href=\"event.php?eventid=".$row['eventid']."\" />".$row['name']."</a></td>";
						echo"<td>".$row['location']."</td>";
						echo"<td>".$row['datetime']."</td>";
						echo "</tr>\n";
					}
				}
				echo "</table>\n";
				// Add Event Tab	
				echo "<br>";
				echo "<button onclick=\"window.location.href='addevent.php'\" class='btn-style'>Add Event</button>";
				echo "</div>";
				$result_event->free();
				break;	


	}
		}
?>

<!DOCTYPE html>
<html lang="en">
	<style>
		.btn-style {
		background-color: #007bff;
		color: white;
		border: none;
		padding: 10px 15px;
		font-size: 16px;
		cursor: pointer;
		border-radius: 5px;
		}
		.btn-style:hover {
			background-color: #0056b3;
		}
		.button-container {
		display: flex;
		justify-content: center;
		gap: 40px; /* Increased space between buttons */
		margin-top: 20px;
	}
	</style>
</html>
