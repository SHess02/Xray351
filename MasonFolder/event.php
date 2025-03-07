<?php
	include '../includes/includes.php';


	$db = new Database();
	
	$select = 'select * from event';
	if (@$_GET['eventid'] != "") {
		$select .= ' where eventid = "'.$_GET['eventid'].'"';
	}
	
	$result = $db->query( $select );
	$rows   = $result->num_rows;
	
	if ($rows == 0){
		echo "Event not found";
	}
	else {
		$job = $result->fetch_assoc();
		echo "<p>".$job['name']."</p>";
		echo "<p>".$job['location']."</p>";
		echo "<p>".$job['datetime']."</p>";
	}

	
	
	// Return to browsing tab
	echo "<button onclick='history.back()'>Go Back</button>";
?>

