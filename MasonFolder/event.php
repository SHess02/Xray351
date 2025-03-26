<?php
    session_start();
    include '../includes/session_check.php';
    include '../includes/includes.php';

    $db = new Database();

    $select = 'SELECT * FROM event';
    if (!empty($_GET['eventid'])) {
        $select .= ' WHERE eventid = "'.$_GET['eventid'].'"';
    }

    $result = $db->query($select);
    $rows = $result->num_rows;

    echo "<style>
        .event-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            background-color: #f9f9f9;
        }
        .event-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .event-location {
            font-size: 18px;
            font-style: italic;
            margin-bottom: 15px;
        }
        .event-datetime {
            font-size: 16px;
            color: #666;
            margin-bottom: 10px;
        }
		.button-container {
			margin-top: 20px;
			display: flex;
			justify-content: center; /* Center buttons horizontally */
			gap: 20px; /* Adds space between buttons */
		}
		.back-button {
			padding: 12px 20px;
			background-color: #007bff;
			color: white;
			text-decoration: none;
			border-radius: 5px;
			border: none;
			cursor: pointer;
			min-width: 150px; /* Ensures buttons have a uniform width */
			text-align: center;
		}
		.back-button:hover {
			background-color: #0056b3;
		}
    </style>";

    if ($rows == 0) {
        echo "<div class='event-container'><p>Event not found</p></div>";
    } else {
        $event = $result->fetch_assoc();
        echo "<div class='event-container'>";
        echo "<p class='event-name'>" . htmlspecialchars($event['name']) . "</p>";
        echo "<p class='event-location'>Location: " . htmlspecialchars($event['location']) . "</p>";
        echo "<p class='event-datetime'>Date & Time: " . htmlspecialchars($event['datetime']) . "</p>";
        echo "<button class='back-button' onclick='history.back()'>Go Back</button>";
		echo "<button class='back-button' onclick=\"window.location.href='eventedit.php?eventid=" . htmlspecialchars($event['eventid']) . "'\" class='btn-style'>Edit Event</button>";
		echo "<button class='back-button' onclick=\"window.location.href='deleteevent.php?eventid=" . htmlspecialchars($event['eventid']) . "'\" class='btn-style'>Delete Event</button>";
        echo "</div>";
    }
?>
