<?php
	// Every File 
	include '../includes/includes.php';
	
	$db = new Database();
	
	// Running Query to Retrieve Event Information
	$select = 'select * from event';
	if (@$_GET['eventid'] != "") {
		$select .= ' where eventid = "'.$_GET['eventid'].'"';
	}
	
	$result = $db->query( $select );
	$rows   = $result->num_rows;
	
	// Displaying Event Information
	if ($rows == 0){
		echo "Event not found";
	} else {
		$event = $result->fetch_assoc();
		echo "<table border='1'>
			  <tr>
              <th> Name </th>
              <th> Location </th>
              <th> Date & Time</th>
			  <th> Admin Email </th>
			  <th> Alumni Email </th>
              </tr>";
		echo "<tr>
              <td>" . $event["name"] . "</td>
              <td>" . $event["location"] . "</td>
              <td>" . $event["datetime"] . "</td>
			  <td>" . $event["Admin_email"] . "</td>
			  <td>" . $event["Alumni_email"] . "</td>
              </tr>";
	}
	echo "</table>";
	
	// Editing Event Tab
	echo "<div class='button-container'>";
	echo "<button onclick=\"window.location.href='eventedit.php?eventid=" . htmlspecialchars($event['eventid']) . "'\" class='btn-style'>Edit</button>";

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
	