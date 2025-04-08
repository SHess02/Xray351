<?php
session_start();
include '../includes/session_check.php';
include '../includes/includes.php';
include 'db_connect_temp.php';

$userid = $_SESSION['userid'];


if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $conversation_id = $_GET['id'];

    $sql = "SELECT message.messageid, 
                   message.contents, 
                   message.datetime, 
                   CASE WHEN message.senderid = $userid THEN 'You' ELSE user.email END AS sender
            FROM message
            LEFT JOIN user ON user.userid = message.senderid OR user.userid = message.receiverid
            WHERE (message.senderid = $userid OR message.receiverid = $userid)
            AND message.messageid = $conversation_id
            ORDER BY message.datetime ASC";

    $result = $conn->query($sql);
    $messages = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }
    } else {
        echo "No messages found for this conversation.";
    }
} else {
    echo "Invalid conversation ID.";
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

        /* Sent by the user (you) */
        .message-item.sent {
            align-self: flex-end;
            background: #268fff;
            color: #fff;
            border-radius: 10px 10px 0 10px;
            max-width: 60%;
        }

        .message-item.sent:before {
            content: '';
            position: absolute;
            bottom: -10px;
            right: -10px;
            border-left: 10px solid #268fff;
            border-top: 10px solid transparent;
        }

        /* Received message */
        .message-item.received {
            align-self: flex-start;
            background: #f1f1f1;
            color: #333;
            border-radius: 10px 10px 10px 0;
            max-width: 60%;
        }

        .message-item.received:before {
            content: '';
            position: absolute;
            bottom: -10px;
            left: -10px;
            border-right: 10px solid #f1f1f1;
            border-top: 10px solid transparent;
        }

        /* Scrollbar styles */
        .conversation-messages::-webkit-scrollbar {
            width: 8px;
        }

        .conversation-messages::-webkit-scrollbar-thumb {
            background: #ddd;
            border-radius: 4px;
        }

        .conversation-messages::-webkit-scrollbar-thumb:hover {
            background: #ccc;
        }

        .conversation-messages::-webkit-scrollbar-track {
            background: #f4f7fa;
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
                        <div class="message-item <?= ($message['sender'] == 'You') ? 'sent' : 'received'; ?>">
                            <strong><?= htmlspecialchars($message['sender']); ?>:</strong>
                            <p><?= htmlspecialchars($message['contents']); ?></p>
                            <small><?= $message['datetime']; ?></small>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>No messages to display.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
