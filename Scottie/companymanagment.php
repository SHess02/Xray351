
<!DOCTYPE html>
<html lang="en">
<html><body>
<form action="companymanagment.php" method="post">
<input type="text" name="newcompanyname" id="newcompanyname">
<br>
<br><input type="submit" name="submit" id="submit"></br>
</form></body>
</html>

<?php
	
$servername = "localhost";
$userName = "root";
$passWord = "";
$dbname = "alumniconnectdb";


// Create connection
$conn = new mysqli($servername, $userName, $passWord, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


// Fetch the 3 most recent companies
	$query_companies = "SELECT companyid, name FROM company ORDER BY name ASC LIMIT 3";
	$result_companies = $conn->query($query_companies);
	
	
		// Companies list
		echo "<div class='list'>";
		echo "<h3>Hiring Companies</h3>";
		if ($result_companies->num_rows > 0) {
			echo "<ul>";
			while ($row = $result_companies->fetch_assoc()) {
				echo "<li><a href=\"company.php?companyid=" . htmlspecialchars($row['companyid']) . "\">" . htmlspecialchars($row['name']) . "</a></li>";
			}
			echo "</ul>";
		} else {
			echo "<p>No hiring companies found.</p>";
		}
		echo "<a href='view_all.php?type=companies' class='view-all-link'>View All Companies</a>";
		echo "</div>";
/**
		
// Update company's name

If (isset($_POST['companyname'])) {
	$companyname = $_POST['companyname'];	
	$query = "SELECT * FROM company WHERE name = '$companyname'";
	$result = mysqli_query($conn, $query);
	if(mysqli_num_rows($result) > 0) {
		$newcompanyname = $_POST['newcompanyname'];
		$companynamesql = "UPDATE company SET name='$newcompanyname' WHERE name='$companyname'";
	}
	else {
		echo "This company does not exist in our database.";
	}
}

If ($_POST['newcompanyname'] != null) {
	if ($conn->query($companynamesql) === TRUE) {
		echo "Record updated successfully";
	} 
	else {
		echo "Error updating record: " . $conn->error;
	}
}

*/

// Displays Table
	$query = "SELECT * FROM company";
	$result = mysqli_query($conn, $query);
	
if ($result->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>Company Name</th>
                <th> Company Description</th>
                <th> Number of Active Listings</th>
            </tr>";
    
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["name"] . "</td>
                <td>" . $row["description"] . "</td>
                <td>" . $row["activelistings"] . "</td>
              </tr>";
    }
    echo "</table>";
}

?>