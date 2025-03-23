<?php
    session_start();
    include '../includes/session_check.php';
    include '../includes/includes.php';

    $db = new Database();

    $select = 'SELECT * FROM job';
    if (!empty($_GET['jobid'])) {
        $select .= ' WHERE jobid = "'.$_GET['jobid'].'"';
    }

    $result = $db->query($select);
    $rows = $result->num_rows;

    echo "<style>
        .job-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            background-color: #f9f9f9;
        }
        .job-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .job-description {
            font-size: 16px;
            margin-bottom: 15px;
        }
        .job-date {
            font-size: 14px;
            color: #666;
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
        echo "<div class='job-container'><p>Job not found</p></div>";
    } else {
        $job = $result->fetch_assoc();
        echo "<div class='job-container'>";
        echo "<p class='job-title'>" . htmlspecialchars($job['title']) . "</p>";
        echo "<p class='job-description'>" . nl2br(htmlspecialchars($job['description'])) . "</p>";
        echo "<p class='job-date'>Posted on: " . htmlspecialchars($job['opendate']) . "</p>";
        echo "<button class='back-button' onclick='history.back()'>Go Back</button>";
        echo "</div>";
    }
?>
