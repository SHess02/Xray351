<?php
session_start();
include '../includes/session_check.php';
include '../includes/includes.php';

$db = new Database();

// Ensure the user has a favorite list
$userid = $_SESSION['userid'];
$query = "INSERT INTO favorite_list (userid)  
          SELECT $userid FROM DUAL  
          WHERE NOT EXISTS (  
              SELECT 1 FROM favorite_list WHERE userid = $userid  
          )";
$db->query($query);

// Get listid
$select_fav_list = "SELECT listid FROM favorite_list WHERE userid = $userid";
$listid_result = $db->query($select_fav_list);
$listid_row = $listid_result->fetch_assoc();
$listid = $listid_row['listid'];

// Get event details
$eventid = $_GET['eventid'] ?? null;
if (!$eventid) {
    die("Event ID is required.");
}

$select_event = "SELECT * FROM event WHERE eventid = $eventid";
$event_result = $db->query($select_event);

if ($event_result->num_rows == 0) {
    die("Event not found.");
}
$event = $event_result->fetch_assoc();

// Check if the event is already favorited
$check_fav = "SELECT * FROM favorite_event WHERE eventid = $eventid AND listid = $listid";
$check_result = $db->query($check_fav);
$isFavorited = $check_result->num_rows > 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Details</title>
    <style>
        .event-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            background-color: #f9f9f9;
            position: relative;
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
        .favorite-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            border: none;
            background: none;
            cursor: pointer;
            font-size: 24px;
            color: <?php echo $isFavorited ? 'gold' : 'gray'; ?>;
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
    </style>
    <script>
        function toggleFavorite(eventid) {
            fetch('favorite_event.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'eventid=' + eventid
            })
            .then(response => response.text())
            .then(() => location.reload());
        }
    </script>
</head>
<body>

<div class="event-container">
    <button class="favorite-btn" onclick="toggleFavorite(<?php echo $eventid; ?>)">
        ★
    </button>
    <p class="event-name"><?php echo htmlspecialchars($event['name']); ?></p>
    <p class="event-location">Location: <?php echo htmlspecialchars($event['location']); ?></p>
    <p class="event-datetime">Date & Time: <?php echo htmlspecialchars($event['datetime']); ?></p>
	
	
	<button class="back-button" onclick="history.back()">Go Back</button>
	<?php
	
	function isEventCreator($db, $userid, $eventid) {
		$userid = intval($userid);
		$eventid = intval($eventid);

		$query = "SELECT creatorid FROM event WHERE eventid = $eventid";
		$result = $db->query($query);

		if ($result && $result->num_rows > 0) {
			$row = $result->fetch_assoc();
			return $row['creatorid'] == $userid;
		}

		return false;
	}

	$userid = intval($_SESSION['userid']); // Sanitize the session value as an integer
	$sql = "SELECT role FROM user WHERE userid = $userid";
	$result = $db->query($sql);

	if ($result && $result->num_rows > 0) {
		$row = $result->fetch_assoc();
		$role = $row['role'];

		if (($role === "alumni" || $role === "admin") && isEventCreator($db, $userid, $event['eventid'])) {
			echo "<button class='back-button' onclick=\"window.location.href='eventedit.php?eventid=" . htmlspecialchars($event['eventid']) . "'\">Edit Event</button>";
			echo "<button class='back-button' onclick=\"window.location.href='deleteevent.php?eventid=" . htmlspecialchars($event['eventid']) . "'\">Delete Event</button>";
		}
	}
	?>

</div>

</body>
</html>
