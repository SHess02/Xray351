<?php
    session_start();
    include '../includes/session_check.php';
    include '../includes/includes.php';

    $db = new Database();
    echo "<h1>Browsing Tab</h1>";

    $today = date('Y-m-d'); // Get today's date
    $active_only = isset($_GET['active_only']) && $_GET['active_only'] == '1'; // Ensure it toggles properly

    // Toggle button form
    echo "<form method='GET' style='margin-bottom: 20px;'>";
    if ($active_only) {
        echo "<button type='submit'>Show All</button>"; // Clicking removes the filter
    } else {
        echo "<input type='hidden' name='active_only' value='1'>";
        echo "<button type='submit'>Show Active Only</button>"; // Clicking enables the filter
    }
    echo "</form>";

    // Modify queries based on active_only toggle
    $job_filter = $active_only ? "WHERE closedate IS NULL OR closedate >= '$today'" : "";
    $company_filter = $active_only ? 
        "JOIN job j ON c.companyid = j.alumniid WHERE j.closedate IS NULL OR j.closedate >= '$today'" : "";
    $event_filter = $active_only ? "WHERE datetime >= NOW()" : "";

    // Fetch the 3 most recent jobs
    $query_jobs = "SELECT jobid, title FROM job $job_filter ORDER BY opendate DESC LIMIT 3";
    $result_jobs = $db->query($query_jobs);

    // Fetch the 3 most recent companies
    $query_companies = "SELECT DISTINCT c.companyid, c.name FROM company c $company_filter ORDER BY c.name ASC LIMIT 3";
    $result_companies = $db->query($query_companies);

    // Fetch the 3 most recent events
    $query_events = "SELECT eventid, name FROM event $event_filter ORDER BY datetime ASC LIMIT 3";
    $result_events = $db->query($query_events);

    echo "<div class='lists-container'>";

    // Jobs list
    echo "<div class='list'>";
    echo "<h3>Recent Jobs</h3>";
    if ($result_jobs->num_rows > 0) {
        echo "<ul>";
        while ($row = $result_jobs->fetch_assoc()) {
            echo "<li><a href=\"job.php?jobid=" . htmlspecialchars($row['jobid']) . "\">" . htmlspecialchars($row['title']) . "</a></li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No recent jobs found.</p>";
    }
    echo "<a href='view_all.php?type=jobs' class='view-all-link'>View All Jobs</a>";
    echo "</div>";

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

    // Events list
    echo "<div class='list'>";
    echo "<h3>Recent Events</h3>";
    if ($result_events->num_rows > 0) {
        echo "<ul>";
        while ($row = $result_events->fetch_assoc()) {
            echo "<li><a href=\"event.php?eventid=" . htmlspecialchars($row['eventid']) . "\">" . htmlspecialchars($row['name']) . "</a></li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No recent events found.</p>";
    }
    echo "<a href='view_all.php?type=events' class='view-all-link'>View All Events</a>";
    echo "</div>";

    echo "</div>"; // Close lists container

    $result_jobs->free();
    $result_companies->free();
    $result_events->free();
?>
