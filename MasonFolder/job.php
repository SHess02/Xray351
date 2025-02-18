<?php
	$db = new mysqli('localhost','root','','alumniconnectdb');
	$db->set_charset("utf8");

	if ($db->connect_errno) {
		echo '<h3>Database Access Error!</h3>';
	}
	else {
		$select = 'select * from job';
		if (@$_GET['jobid'] != "") {
			$select .= ' where jobid = "'.$_GET['jobid'].'"';
		}
		
		$result = $db->query( $select );
		$rows   = $result->num_rows;
		
		if ($rows == 0){
			echo "Job not found";
		}
		else {
			$job = $result->fetch_assoc();
			echo "<p>".$job['title']."</p>";
			echo "<p>".$job['description']."</p>";
			echo "<p>".$job['opendate']."</p>";
		}
		
	}
	
	// Return to browsing tab
	echo "<button onclick='history.back()'>Go Back</button>";

?>