<?php
    if (isset($_GET['type'])) {
		session_start();
		include '../includes/session_check.php';
        include '../includes/includes.php';
        $db = new Database();


        switch ($_GET['type']) {
            case 'jobs':
                $select_job = "SELECT * FROM job";
                $result_job = $db->query($select_job);
                $rows_job = $result_job->num_rows;

                echo "<div class='table-wrapper'>"; // New wrapper for table and button
                echo "<div class='table-container'>"; // Table container for width control
                echo "<table class=\"Browsing Tab\">\n";
                echo "<tr>\n";
                echo "<th>Title</th>";
                echo "<th>Description</th>";
                echo "<th>Date<th>";
                echo "</tr>\n";

                if ($rows_job == 0) {
                    echo "<tr><td colspan=\"3\">Nothing to Display</td></tr>\n";
                } else {
                    while ($row = $result_job->fetch_assoc()) {
                        echo "<tr class=\"highlight\">";
                        echo "<td><a href=\"job.php?jobid=" . $row['jobid'] . "\">" . htmlspecialchars($row['title']) . "</a></td>";
                        echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['opendate']) . "</td>";
                        echo "</tr>\n";
                    }
                }
                echo "</table>\n";
                echo "</div>"; // Close .table-container
					
					
					
				$userid = intval($_SESSION['userid']); // Sanitize the session value as an integer
				$sql = "SELECT role FROM user WHERE userid = $userid";
				$result = $db->query($sql);
				
				if ($result && $result->num_rows > 0) {
					$row = $result->fetch_assoc();
					$role = $row['role'];
					if ($role === "faculty" || $role === "alumni" || $role === "admin"){
						echo "<div class='button-container-left'>";
						echo "<button onclick=\"window.location.href='addjob.php'\" class='btn-style'>Add Job</button>";
						echo "</div>";
					}
				}                

                echo "</div>"; // Close .table-wrapper
                $result_job->free();
                break;

            case 'companies':
                $select_company = "SELECT * FROM company";
                $result_company = $db->query($select_company);
				if (!$result_company) {
					echo "false";
				}
                $rows_company = $result_company->num_rows;

                echo "<div class='table-wrapper'>";
                echo "<div class='table-container'>";
                echo "<table class=\"Browsing Tab\">\n";
                echo "<tr>\n";
                echo "<th>Company Name</th>";
                echo "<th>Description</th>";
                echo "<th>Open Listings</th>";
                echo "</tr>\n";

                if ($rows_company == 0) {
                    echo "<tr><td colspan=\"3\">Nothing to Display</td></tr>\n";
                } else {
                    while ($row = $result_company->fetch_assoc()) {
                        echo "<tr class=\"highlight\">";
                        echo "<td><a href=\"company.php?companyid=" . $row['companyid'] . "\">" . htmlspecialchars($row['name']) . "</a></td>";
                        echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['activelistings']) . "</td>";
                        echo "</tr>\n";
                    }
                }
                echo "</table>\n";
                echo "</div>";
				
				$userid = intval($_SESSION['userid']); // Sanitize the session value as an integer
				$sql = "SELECT role FROM user WHERE userid = $userid";
				$result = $db->query($sql);
				
				if ($result && $result->num_rows > 0) {
					$row = $result->fetch_assoc();
					$role = $row['role'];
					if ($role === "faculty" || $role === "alumni" || $role === "admin"){
						echo "<div class='button-container-left'>";
						echo "<button onclick=\"window.location.href='addcompany.php'\" class='btn-style'>Add Company</button>";
						echo "</div>";
					}
				}
				
                echo "</div>";
                $result_company->free();
                break;

            case 'events':
                $select_event = "SELECT * FROM event";
                $result_event = $db->query($select_event);
                $rows_event = $result_event->num_rows;

                echo "<div class='table-wrapper'>";
                echo "<div class='table-container'>";
                echo "<table class=\"Browsing Tab\">\n";
                echo "<tr>\n";
                echo "<th>Event Name</th>";
                echo "<th>Location</th>";
                echo "<th>Date</th>";
                echo "</tr>\n";

                if ($rows_event == 0) {
                    echo "<tr><td colspan=\"3\">Nothing to Display</td></tr>\n";
                } else {
                    while ($row = $result_event->fetch_assoc()) {
                        echo "<tr class=\"highlight\">";
                        echo "<td><a href=\"event.php?eventid=" . $row['eventid'] . "\">" . htmlspecialchars($row['name']) . "</a></td>";
                        echo "<td>" . htmlspecialchars($row['location']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['datetime']) . "</td>";
                        echo "</tr>\n";
                    }
                }
                echo "</table>\n";
                echo "</div>";
				
				
				$userid = intval($_SESSION['userid']); // Sanitize the session value as an integer
				$sql = "SELECT role FROM user WHERE userid = $userid";
				$result = $db->query($sql);
				
				if ($result && $result->num_rows > 0) {
					$row = $result->fetch_assoc();
					$role = $row['role'];
					if ($role === "faculty" || $role === "alumni" || $role === "admin"){
						echo "<div class='button-container-left'>";
						echo "<button onclick=\"window.location.href='addevent.php'\" class='btn-style'>Add Event</button>";
						echo "</div>";
					}
				}

                echo "</div>";
                $result_event->free();
                break;
        }
    }
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

        /* Container for table and button */
        .table-wrapper {
            display: flex;
            flex-direction: column;
            align-items: flex-start; /* Ensures button stays at the bottom-left */
            max-width: 80%;
            margin: auto;
        }

        /* Table container to limit width */
        .table-container {
            width: 100%;
            overflow-x: auto;
        }

        /* Button container positioned at the bottom-left */
        .button-container-left {
            margin-top: 10px;
            padding-left: 10px;
        }

        /* Table Styling */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        th a {
            color: white;
            text-decoration: none;
        }

        th a:hover {
            text-decoration: underline;
        }

        td {
            font-size: 1.1em;
            color: #333;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e2e6ea;
        }
    </style>
</html>
