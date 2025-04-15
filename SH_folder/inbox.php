
<?php
ob_start();
session_start();
include '../includes/session_check.php';
include '../includes/includes.php';
include 'db_connect_temp.php';

$userid = $_SESSION['userid'];  

# Submission to send a message
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $receiver_email = $_POST['receiver_email']; 
    $message_content = $_POST['message']; 

    // Use prepared statements to avoid SQL injection
    $stmt = $conn->prepare("SELECT userid FROM user WHERE email = ?");
    $stmt->bind_param("s", $receiver_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $receiver_row = $result->fetch_assoc();
        $receiverid = $receiver_row['userid'];

        $datetime = date('Y-m-d H:i:s'); 
        $insert_stmt = $conn->prepare("INSERT INTO message (senderid, receiverid, contents, datetime) VALUES (?, ?, ?, ?)");
        $insert_stmt->bind_param("iiss", $userid, $receiverid, $message_content, $datetime);

        if ($insert_stmt->execute()) {
            // Redirect to prevent form resubmission
            header("Location: inbox.php?sent=1");
            exit();
        } else {
            $error_message = "Error sending message.";
        }
    } else {
        $error_message = "Receiver with email $receiver_email not found!";
    }
}

# Fetch the list of conversations for the logged-in user
$sql = "SELECT message.messageid, 
               CASE WHEN message.senderid = $userid THEN user2.email ELSE user1.email END AS sender, 
               LEFT(message.contents, 50) AS preview
        FROM message
        JOIN user AS user1 ON message.senderid = user1.userid
        JOIN user AS user2 ON message.receiverid = user2.userid
        WHERE message.senderid = $userid OR message.receiverid = $userid
        ORDER BY message.datetime DESC";

$result = $conn->query($sql);
$messages = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
}
ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://unpkg.com/htmx.org@1.8.4"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inbox</title>
	<style>
	  * {
		box-sizing: border-box;
	  }

	  body {
		margin: 0;
		font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
		background: #f6f8fa;
		color: #333;
	  }

	  .container {
		display: flex;
		justify-content: center;
		gap: 40px;
		padding: 60px 20px;
		max-width: 1400px;
		margin: 0 auto;
	  }

	  .inbox, .message-view {
		background: #ffffff;
		border-radius: 12px;
		padding: 30px;
		box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
		flex: 1;
		max-width: 600px;
	  }

	  h2 {
		margin-top: 0;
		font-size: 28px;
		color: #007bff;
		text-align: center;
	  }

	  ul#conversation-list {
		list-style: none;
		padding: 0;
		margin: 0;
	  }

	  .conversation-item {
		background: #f1f5f9;
		padding: 15px 20px;
		border-radius: 10px;
		margin-bottom: 12px;
		transition: background 0.3s;
	  }

	  .conversation-item a {
		text-decoration: none;
		color: #333;
		font-weight: 500;
		display: block;
	  }

	  .conversation-item:hover {
		background: #e2e8f0;
	  }

	  form label {
		display: block;
		margin-bottom: 8px;
		font-weight: bold;
		color: #555;
	  }

	  input[type="email"], textarea {
		width: 100%;
		padding: 12px;
		border-radius: 8px;
		border: 1px solid #ccc;
		margin-bottom: 20px;
		font-size: 1rem;
		background: #fff;
		color: #333;
	  }

	  input[type="email"]::placeholder,
	  textarea::placeholder {
		color: #aaa;
	  }

	  input:focus, textarea:focus {
		outline: none;
		border: 2px solid #007bff;
		background: #f0f8ff;
	  }

	  button[type="submit"] {
		width: 100%;
		padding: 12px;
		background: #007bff;
		color: #fff;
		border: none;
		border-radius: 8px;
		font-weight: bold;
		font-size: 1rem;
		cursor: pointer;
		transition: background 0.3s;
	  }

	  button:hover {
		background: #005fcc;
	  }

	  .dropdown-container {
		position: absolute;
		background-color: #fff;
		width: 100%;
		max-height: 200px;
		overflow-y: auto;
		box-shadow: 0 4px 10px rgba(0,0,0,0.1);
		z-index: 999;
		display: none;
		border-radius: 8px;
		border: 1px solid #ccc;
	  }

	  .search-result-item {
		padding: 12px 16px;
		cursor: pointer;
		color: #333;
		border-bottom: 1px solid #eee;
	  }

	  .search-result-item:hover {
		background: #f5f5f5;
	  }

	  .status-message {
		text-align: center;
		margin-bottom: 20px;
		font-size: 1rem;
		padding: 10px 15px;
		border-radius: 8px;
	  }

	  .status-success {
		background-color: #d1f3dd;
		color: #206f3d;
		border: 1px solid #a4dfb9;
	  }

	  .status-error {
		background-color: #fde2e2;
		color: #a33131;
		border: 1px solid #f5a7a7;
	  }

	  @media (max-width: 900px) {
		.container {
		  flex-direction: column;
		  padding: 20px;
		}
	  }
	</style>

</head>
<body>
<div class="container">
    <div class="inbox">
        <h2>Inbox</h2>

        <?php if (isset($_GET['sent']) && $_GET['sent'] == 1): ?>
            <p style="color: lightgreen; text-align: center;">✅ Message sent successfully!</p>
        <?php elseif (!empty($error_message)): ?>
            <p style="color: pink; text-align: center;"><?= htmlspecialchars($error_message) ?></p>
        <?php endif; ?>

        <ul id="conversation-list">
            <?php foreach ($messages as $conversation): ?>
                <li class="conversation-item">
                    <a href="conversation.php?id=<?= $conversation['messageid'] ?>">
                        <?= htmlspecialchars($conversation['sender']) ?>: <?= htmlspecialchars($conversation['preview']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="message-view">
        <h2>Send a Message</h2>
        <form action="inbox.php" method="POST">
            <label for="receiver_email">To:</label>
            <input 
                type="email" 
                id="receiver_email" 
                name="receiver_email" 
                required 
                hx-get="search_user.php"  
                hx-target="#search-results"  
                hx-trigger="keyup delay:500ms"  
                hx-params="receiver_email"
            >
            <div id="search-results" class="dropdown-container"></div>

            <label for="message">Message:</label>
            <textarea name="message" id="message" required></textarea>

            <button type="submit">Send</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchResultsContainer = document.getElementById('search-results');
        const receiverEmailInput = document.getElementById('receiver_email');

        searchResultsContainer.addEventListener('click', function(event) {
            if (event.target && event.target.classList.contains('search-result-item')) {
                const selectedEmail = event.target.getAttribute('data-email');
                receiverEmailInput.value = selectedEmail; 
                searchResultsContainer.innerHTML = '';
                searchResultsContainer.style.display = 'none'; 
            }
        });

        document.addEventListener('click', function(event) {
            if (!searchResultsContainer.contains(event.target) && event.target !== receiverEmailInput) {
                searchResultsContainer.innerHTML = ''; 
                searchResultsContainer.style.display = 'none'; 
            }
        });

        document.body.addEventListener('htmx:afterSwap', function(event) {
            if (event.target.id === 'search-results') {
                if (searchResultsContainer.children.length > 0) {
                    searchResultsContainer.style.display = 'block';
                } else {
                    searchResultsContainer.style.display = 'none'; 
                }
            }
        });
    });
</script>

</body>
</html>
