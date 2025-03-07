
<?php

// Making sure database & includes are in file
include '../includes/includes.php';

$db = new Database();

$query = "SELECT * FROM event";

$result = mysqli_query($db, $query);

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