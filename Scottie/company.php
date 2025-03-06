
	<!DOCTYPE html>
	<html lang="en">
	<html><body>
	<form action="companyedit.php" method="get">
    <button type="submit">Edit Company Data</button>
	</form>
	<br>
	</body>
	</html>	
<?php

	$db = new mysqli('localhost','root','','alumniconnectdb');
	$db->set_charset("utf8");

	if ($db->connect_errno) {
		echo '<h3>Database Access Error!</h3>';
	}
	else {
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
			$company = $result->fetch_assoc();
			echo "<table border='1'>
            <tr>
                <th>Company Name</th>
                <th> Company Description</th>
                <th> Number of Active Listings</th>
            </tr>";
			echo "<tr>
                <td>" . $company["name"] . "</td>
                <td>" . $company["description"] . "</td>
                <td>" . $company["activelistings"] . "</td>
              </tr>";
			}
			echo "</table>";
		}
	// Return to browsing tab
	echo "<br>";
	echo "<button onclick='history.back()'>Go Back</button>";
	
?>