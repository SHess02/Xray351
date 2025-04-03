<?php
session_start();
// Adjust path to session_check.php and includes.php
include '../../includes/session_check.php';
include '../../includes/includes.php';

$db = new Database();
$basePath = "/Xray351/MasonFolder/";

if (isset($_GET['query']) && isset($_GET['category'])) {
    $query = $db->escape($_GET['query']);
    $category = $_GET['category'];

    switch ($category) {
        case 'jobs':
            $sql = "SELECT jobid, title, description FROM job WHERE title LIKE '%$query%' OR description LIKE '%$query%'";
            break;
        case 'companies':
            $sql = "SELECT companyid, name, description FROM company WHERE name LIKE '%$query%' OR description LIKE '%$query%'";
            break;
        case 'events':
            $sql = "SELECT eventid, name, location FROM event WHERE name LIKE '%$query%' OR location LIKE '%$query%'";
            break;
        case 'users':
            $sql = "SELECT userid, name, email FROM user WHERE name LIKE '%$query%' OR email LIKE '%$query%'";
            break;
        default:
            echo "<div class='no-results'>Invalid category.</div>";
            exit();
    }

    $result = $db->query($sql);

    echo "<div class='search-results-container'>";
    echo "<h2 class='search-results-title'>Search Results</h2>";

    if ($result->num_rows > 0) {
        echo "<table class='search-results-table'>";
        echo "<tr>";
        
        // Define table headers based on category
        switch ($category) {
            case 'jobs':
                echo "<th>Title</th><th>Description</th>";
                break;
            case 'companies':
                echo "<th>Company Name</th><th>Description</th>";
                break;
            case 'events':
                echo "<th>Event Name</th><th>Location</th>";
                break;
            case 'users':
                echo "<th>Name</th><th>Email</th>";
                break;
        }

        echo "</tr>";

        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            switch ($category) {
                case 'jobs':
                    echo "<td><a href='{$basePath}job.php?jobid=" . $row['jobid'] . "'>" . htmlspecialchars($row['title']) . "</a></td>";
                    echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                    break;
                case 'companies':
                    echo "<td><a href='{$basePath}company.php?companyid=" . $row['companyid'] . "'>" . htmlspecialchars($row['name']) . "</a></td>";
                    echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                    break;
                case 'events':
                    echo "<td><a href='{$basePath}event.php?eventid=" . $row['eventid'] . "'>" . htmlspecialchars($row['name']) . "</a></td>";
                    echo "<td>" . htmlspecialchars($row['location']) . "</td>";
                    break;
                case 'users':
                    echo "<td><a href='{$basePath}profile.php?userid=" . $row['userid'] . "'>" . htmlspecialchars($row['name']) . "</a></td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    break;
            }
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<div class='no-results'>No results found.</div>";
    }

    echo "</div>";
    $result->free();
}
?>

<style>

	/* Search Results Container */
	.search-results-container {
		width: 80%;
		margin: 20px auto;
		font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
		background-color: #f8f9fa;
		border-radius: 8px;
		padding: 15px;
		box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
	}

	/* Search Results Title */
	.search-results-title {
		font-size: 24px;
		font-weight: bold;
		color: #333;
		margin-bottom: 15px;
		text-align: center;
	}

	/* Table Styling */
	.search-results-table {
		width: 100%;
		border-collapse: collapse;
		background-color: #ffffff;
		border-radius: 8px;
		overflow: hidden;
	}

	.search-results-table th, 
	.search-results-table td {
		border: 1px solid #ddd;
		padding: 12px;
		text-align: left;
	}

	.search-results-table th {
		background-color: #007bff;
		color: white;
		font-weight: bold;
	}

	.search-results-table td {
		font-size: 1.1em;
		color: #333;
	}

	/* Alternate row color */
	.search-results-table tr:nth-child(even) {
		background-color: #f2f2f2;
	}

	/* Hover effect */
	.search-results-table tr:hover {
		background-color: #e2e6ea;
	}

	/* No Results Message */
	.no-results {
		text-align: center;
		font-size: 18px;
		color: #666;
		padding: 20px;
	}

	/* Links inside table */
	.search-results-table a {
		color: #007bff;
		text-decoration: none;
		font-weight: bold;
	}

	.search-results-table a:hover {
		text-decoration: underline;
	}

	/* Responsive Design */
	@media (max-width: 768px) {
		.search-results-container {
			width: 95%;
		}

		.search-results-table th, 
		.search-results-table td {
			padding: 8px;
		}
	}

</style>
