

<?php
	// Every File 
	include '../includes/includes.php';
	
	$db = new Database();
	
	// Running Query to Retrieve Event Information
	$select = 'select * from company';
	if (@$_GET['companyid'] != "") {
		$select .= ' where companyid = "'.$_GET['companyid'].'"';
	}
	
	$result = $db->query( $select );
	$rows   = $result->num_rows;
	
	// Displaying Company Information
	if ($rows == 0){
		echo "Company not found";
	}
	else {
		$company = $result->fetch_assoc();
		echo "<table border='1'>
			  <tr>
              <th> Company Name </th>
              <th> Company Description </th>
              <th> Number of Active Listings </th>
              </tr>";
		echo "<tr>
              <td>" . $company["name"] . "</td>
              <td>" . $company["description"] . "</td>
              <td>" . $company["activelistings"] . "</td>
              </tr>";
			}
		echo "</table>";
		
	
	// Editing tab
	echo "<br>";
	echo "<button onclick=\"window.location.href='companyedit.php?companyid=" . htmlspecialchars($company['companyid']) . "'\" 
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