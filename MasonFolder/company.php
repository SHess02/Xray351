<?php
	include '../includes/includes.php';


	$db = new Database();
	
	$select = 'select * from company';
	if (@$_GET['companyid'] != "") {
		$select .= ' where companyid = "'.$_GET['companyid'].'"';
	}
	
	$result = $db->query( $select );
	$rows   = $result->num_rows;
	
	if ($rows == 0){
		echo "Company not found";
	}
	else {
		$job = $result->fetch_assoc();
		echo "<p>".$job['name']."</p>";
		echo "<p>".$job['description']."</p>";
		echo "<p>".$job['activelistings']."</p>";
	}
		
	
	
	// Return to browsing tab
	//echo "<button onclick='history.back()'>Go Back</button>";
?>