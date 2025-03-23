<?php
    session_start();
    include '../includes/session_check.php';
    include '../includes/includes.php';

    $db = new Database();

    $select = 'SELECT * FROM company';
    if (!empty($_GET['companyid'])) {
        $companyid = $_GET['companyid'];
        $select .= ' WHERE companyid = "' . $companyid . '"';
    }

    $result = $db->query($select);
    $rows = $result->num_rows;

    echo "<style>
        .company-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            background-color: #f9f9f9;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .company-description {
            font-size: 16px;
            margin-bottom: 15px;
        }
        .active-jobs {
            margin-top: 20px;
        }
        .job-item {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .job-item:last-child {
            border-bottom: none;
        }
        .back-button {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            cursor: pointer;
        }
        .back-button:hover {
            background-color: #0056b3;
        }
    </style>";

    if ($rows == 0) {
        echo "<div class='company-container'><p>Company not found</p></div>";
    } else {
        $company = $result->fetch_assoc();
        echo "<div class='company-container'>";
        echo "<p class='company-name'>" . htmlspecialchars($company['name']) . "</p>";
        echo "<p class='company-description'>" . htmlspecialchars($company['description']) . "</p>";
        echo "<p>Active Listings: " . htmlspecialchars($company['activelistings']) . "</p>";

        // Fetch active jobs from this company
        $query_jobs = "SELECT jobid, title, opendate, closedate 
                       FROM job 
                       WHERE alumniid = '$companyid' 
                       AND closedate >= CURDATE()";

        $result_jobs = $db->query($query_jobs);

        if ($result_jobs->num_rows > 0) {
            echo "<div class='active-jobs'>";
            echo "<h3>Active Job Postings</h3>";
            while ($job = $result_jobs->fetch_assoc()) {
                echo "<div class='job-item'>";
                echo "<p><a href='job.php?jobid=" . htmlspecialchars($job['jobid']) . "'>" . htmlspecialchars($job['title']) . "</a></p>";
                echo "<p>Open Date: " . htmlspecialchars($job['opendate']) . "</p>";
                echo "<p>Closing Date: " . htmlspecialchars($job['closedate']) . "</p>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<p>No active job postings at this time.</p>";
        }

        echo "<button class='back-button' onclick='history.back()'>Go Back</button>";
        echo "</div>";
    }
?>
