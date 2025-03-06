
	<!DOCTYPE html>
	<html lang="en">
	<link rel="stylesheet" href="styles.css">
	<html><body>
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
	// Editing tab
	echo "<li><a href=\"companyedit.php?companyid=" . htmlspecialchars($company['companyid']) . "\">" . htmlspecialchars($company['name']) . "</a></li>";
	// Return to companymanagment tab
	echo "<br>";
	echo "<a href=\"companymanagment.php" style="display: inline-block; padding: 10px 20px; background-color: blue; color: white; text-decoration: none; border-radius: 5px;">Go to Back to Company Managment</a>";
?>