<?php
include 'db_connect_temp.php'; 

if (isset($_GET['receiver_email'])) {
    $searchQuery = $_GET['receiver_email']; 
    $searchQuery = '%' . $conn->real_escape_string($searchQuery) . '%'; 


    $sql = "SELECT email FROM user WHERE email LIKE ? LIMIT 5";
    

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $searchQuery); 
        
        $stmt->execute();
        
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {

            echo "<ul class='search-results-dropdown'>";
            while ($row = $result->fetch_assoc()) {
                echo "<li class='search-result-item' data-email='" . htmlspecialchars($row['email']) . "'>" . htmlspecialchars($row['email']) . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>No users found.</p>";
        }

        $stmt->close();
    } else {
        echo "<p>Error preparing the query.</p>";
    }
}
?>
