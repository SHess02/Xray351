<?php
	// Every File 
	include '../includes/includes.php';
	
	$db = new Database();
	
	// Running Query to Retrieve Event Information
	$select = 'select * from job';
	if (@$_GET['jobid'] != "") {
		$select .= ' where jobid = "'.$_GET['jobid'].'"';
	}
	
	$result = $db->query( $select );
	$rows   = $result->num_rows;
	
	// Displaying Job Information
	if ($rows == 0){
		echo "Job not found";
	}
	else {
		$job = $result->fetch_assoc();
		echo "<table border='1'>
			  <tr>
              <th> Title </th>
              <th> Description </th>
              <th> Open Date </th>
              </tr>";
		echo "<tr>
              <td>" . $job["title"] . "</td>
              <td>" . $job["description"] . "</td>
              <td>" . $job["opendate"] . "</td>
              </tr>";
			}
		echo "</table>";
		
	
	// Editing tab
	echo "<br>";
	echo "<button onclick=\"window.location.href='jobedit.php?jobid=" . htmlspecialchars($job['jobid']) . "'\" 
        class='btn-style'>Edit</button>";
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
	</style>
<html><body>
</body></html>	