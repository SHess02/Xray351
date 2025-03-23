<?php
    session_start();
    include '../includes/session_check.php';
    include '../includes/includes.php';

    $db = new Database();

    if (!isset($_GET['query']) || empty(trim($_GET['query']))) {
        echo "<p>Please enter a search term.</p>";
        exit;
    }

    $search = $db->real_escape_string($_GET['query']);

    // Query jobs
    $query_jobs = "SELECT jobid, title FROM job WHERE title LIKE '%$search%' OR description LIKE '%$search%'";

    // Query companies
    $query_companies = "SELECT companyid, name FROM company WHERE name LIKE '%$search%' OR description LIKE '%$search%'";

    // Query events
    $query_events = "SELECT eventid, name FROM event WHERE name LIKE '%$search%' OR location LIKE '%$search%'";

    $result_jobs = $db->query($query_jobs);
    $result_companies = $db->query($query_companies);
    $result_events = $db->query($query_events);

    echo "<style>
        .search-results { max-width: 800px; margin: 20px auto; padding: 20px; background: #fff; box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); border-radius: 8px; }
        .result-category { margin-top: 20px; padding: 10px; border-bottom: 1px solid #ddd; }
        .result-item { margin-left: 15px; }
    </style>";

    echo "<div class='search-results'>";
    echo "<h2>Search Results for: " . htmlspecialchars($search) . "</h2>";

    // Display jobs
    echo "<div class='result-category'><h3>Jobs</h3>";
    if ($result_jobs->num_rows > 0) {
        while ($job = $result_jobs->fetch_assoc()) {
            echo "<p class='result-item'><a href='job.php?jobid=" . htmlspecialchars($job['jobid']) . "'>" . htmlspecialchars($job['title']) . "</a></p>";
        }
    } else {
        echo "<p class='result-item'>No jobs found.</p>";
    }
    echo "</div>";

    // Display companies
    echo "<div class='result-category'><h3>Companies</h3>";
    if ($result_companies->num_rows > 0) {
        while ($company = $result_companies->fetch_assoc()) {
            echo "<p class='result-item'><a href='company.php?companyid=" . htmlspecialchars($company['companyid']) . "'>" . htmlspecialchars($company['name']) . "</a></p>";
        }
    } else {
        echo "<p class='result-item'>No companies found.</p>";
    }
    echo "</div>";

    // Display events
    echo "<div class='result-category'><h3>Events</h3>";
    if ($result_events->num_rows > 0) {
        while ($event = $result_events->fetch_assoc()) {
            echo "<p class='result-item'><a href='event.php?eventid=" . htmlspecialchars($event['eventid']) . "'>" . htmlspecialchars($event['name']) . "</a></p>";
        }
    } else {
        echo "<p class='result-item'>No events found.</p>";
    }
    echo "</div>";

    echo "<button onclick='history.back()'>Go Back</button>";
    echo "</div>";
?>
