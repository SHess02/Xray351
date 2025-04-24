<?php
// Shane Hess
session_start();
include '../includes/session_check.php';
include '../includes/includes.php';
include 'db_connect_temp.php';

$userid = $_SESSION['userid'];
$messages = [];
$other_userid = null;
$error_message = '';
$success_message = '';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $message_id = intval($_GET['id']);

    $stmt = $conn->prepare("SELECT senderid, receiverid FROM message WHERE messageid = ?");
    $stmt->bind_param("i", $message_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $senderid = $row['senderid'];
        $receiverid = $row['receiverid'];

        # Determine who the other user is
        $other_userid = ($senderid == $userid) ? $receiverid : $senderid;

        # Handle message sending if POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
            $new_message = trim($_POST['message']);
            if (!empty($new_message)) {
                $datetime = date('Y-m-d H:i:s');
                $send_stmt = $conn->prepare("INSERT INTO message (senderid, receiverid, contents, datetime) VALUES (?, ?, ?, ?)");
                $send_stmt->bind_param("iiss", $userid, $other_userid, $new_message, $datetime);
                if ($send_stmt->execute()) {
                    $success_message = "Message sent!";
                } else {
                    $error_message = "Error sending message.";
                }
                $send_stmt->close();
            }
        }

       # fetches full conversation
        $sql = "SELECT messageid, senderid, contents, datetime, u.email AS sender
                FROM message
                JOIN user u ON u.userid = message.senderid
                WHERE (senderid = ? AND receiverid = ?)
                   OR (senderid = ? AND receiverid = ?)
                ORDER BY datetime ASC";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiii", $userid, $other_userid, $other_userid, $userid);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }
    } else {
        echo "Message not found.";
        exit;
    }

    $stmt->close();
} else {
    echo "Invalid conversation ID.";
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversation</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #f4f7fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            height: 100vh;
        }

        .container {
            width: 90%;
            max-width: 800px;
            margin-top: 50px;
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            height: 80vh;
        }

        .conversation h2 {
            text-align: center;
            color: #333;
        }

        .conversation-messages {
            display: flex;
            flex-direction: column;
            gap: 20px;
            max-height: 60vh;
            overflow-y: auto;
            padding-bottom: 10px;
        }

        .message-item {
            display: flex;
            flex-direction: column;
            max-width: 70%;
            padding: 10px;
            border-radius: 10px;
            position: relative;
            font-size: 1rem;
        }

        .message-item p {
            margin: 0;
            font-size: 1rem;
        }

        .message-item small {
            margin-top: 5px;
            font-size: 0.8rem;
            color: #888;
        }

        .message-item.sent {
            align-self: flex-end;
            background: #268fff;
            color: #fff;
            border-radius: 10px 10px 0 10px;
            max-width: 60%;
        }

        .message-item.received {
            align-self: flex-start;
            background: #f1f1f1;
            color: #333;
            border-radius: 10px 10px 10px 0;
            max-width: 60%;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="conversation">
            <h2>Conversation</h2>

            <?php if (count($messages) > 0): ?>
                <div class="conversation-messages">
					<?php foreach ($messages as $message): ?>
					  <div class="message-item <?= ($message['senderid'] == $userid) ? 'sent' : 'received'; ?>">
						<strong><?= ($message['senderid'] == $userid) ? 'You' : htmlspecialchars($message['sender']); ?>:</strong>
						<p><?= htmlspecialchars($message['contents']); ?></p>
						<small><?= $message['datetime']; ?></small>
					  </div>
					<?php endforeach; ?>
                </div>
				
					<!-- 👇 Place the form right here -->
					<?php if (!empty($error_message)): ?>
						<p style="color: red; text-align:center;"><?= htmlspecialchars($error_message) ?></p>
					<?php elseif (!empty($success_message)): ?>
						<p style="color: green; text-align:center;"><?= htmlspecialchars($success_message) ?></p>
					<?php endif; ?>

					<form method="POST" style="margin-top: 20px;">
						<label for="message">Reply:</label>
						<textarea 
							name="message" 
							id="message" 
							rows="4" 
							style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ccc;" 
							required
						></textarea>
						<button 
							type="submit" 
							style="margin-top: 10px; padding: 12px 20px; background-color: #007bff; color: #fff; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;"
						>
							Send
						</button>
					</form>

            <?php else: ?>
                <p>No messages to display.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
