<?php
	$db = new mysqli('localhost','root','','alumniconnectdb');
	$db->set_charset("utf8");

	if ($db->connect_errno) {
		echo '<h3>Database Access Error!</h3>';
	}
	else {
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
		
	}
?>