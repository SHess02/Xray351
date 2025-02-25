<!DOCTYPE html>
<html lang="en">
<html><body>
<p> Enter the Company You Wish to Edit </p>
<form action="companymanagment.php" method="post">
<input type="text" name="companyname" id="companyname">
<p> Enter the Company Name You Wish to Change it To. </p>
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

If (isset($_POST['companyname'])) {
	$companyname = $_POST['companyname'];	
	$query = "SELECT * FROM company WHERE name = '$companyname'";
	$result = mysqli_query($conn, $query);
	if(mysqli_num_rows($result) > 0) {
		If (isset($_POST['newcompanyname'])) {
			$companyname = $_POST['newcompanyname'];
			$sql = "UPDATE company SET name = $companyname WHERE id = ?";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param("si", $new_name, $company_id);

			if ($stmt->execute()) {
				echo "Company name updated successfully.";
			} 
			else {
				echo "Error updating company name: " . $conn->error;
			}
		}
		else {
			echo "This company does not exist in our database.";
		}
	}
}

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
} else {
    echo "No records found.";
}

?>