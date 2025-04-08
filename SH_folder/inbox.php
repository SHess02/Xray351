<?php
	session_start();
	include '../includes/session_check.php';
	include '../includes/includes.php';
	include 'db_connect_temp.php';




$userid = $_SESSION['userid'];  

# submission to send a message
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $receiver_email = $_POST['receiver_email']; 
    $message_content = $_POST['message']; 

    $receiver_query = "SELECT userid FROM user WHERE email = '$receiver_email'";
    $receiver_result = $conn->query($receiver_query);

    if ($receiver_result->num_rows > 0) {
        $receiver_row = $receiver_result->fetch_assoc();
        $receiverid = $receiver_row['userid'];


        $datetime = date('Y-m-d H:i:s'); 
        $insert_message_query = "INSERT INTO message (senderid, receiverid, contents, datetime) 
                                 VALUES ($userid, $receiverid, '$message_content', '$datetime')";

        if ($conn->query($insert_message_query)) {
            echo "Message sent successfully!";
        } else {
            echo "Error sending message: " . $conn->error;
        }
    } else {
        echo "Receiver with email $receiver_email not found!";
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<script src="https://unpkg.com/htmx.org@1.8.4"></script>

	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Inbox</title>
		<style>

		  body {
			background: linear-gradient(135deg, #6a11cb, #2575fc);
			color: #fff;
			min-height: 100vh;
			display: flex;
			flex-direction: column;
			justify-content: flex-start;
			align-items: center;
			font-family: 'Arial', sans-serif;
			margin: 0;
			padding: 0;
		  }

		  .container {
			width: 90%;
			max-width: 1200px;
			margin-top: 50px;
			display: flex;
			justify-content: space-between;
			gap: 30px;
		  }


		  .inbox {
			background: rgba(255, 255, 255, 0.1);
			padding: 30px;
			margin-top: 50px;
			border-radius: 10px;
			box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
			margin-bottom: 30px;
			flex: 1;
			max-width: 600px;
		  }
		  
		  .dropdown-container {
            position: absolute;
            background-color: white;
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            z-index: 999;
            display: none;
        }
			.search-results {
				display: none; /* Hidden by default */
				position: absolute; /* Absolute positioning */
				background-color: white; /* White background for visibility */
				width: 100%; /* Same width as the input field */
				max-height: 150px; /* Reduced height */
				overflow-y: auto; /* Enable scrolling if content exceeds max height */
				box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
				z-index: 999; /* Ensure it appears above other elements */
				border-radius: 5px;
				margin-top: 5px; /* Space between input field and dropdown */
			}

			.search-results-dropdown {
				list-style: none;
				margin: 0;
				padding: 0;
			}

			.search-result-item {
				padding: 10px;
				cursor: pointer;
				font-size: 1rem;
				color: #333; /* Set text color to dark */
				border-bottom: 1px solid #ddd;
			}

			.search-result-item:hover {
				background-color: #f1f1f1; /* Highlight on hover */
			}

			.search-result-item:focus {
				background-color: #ddd; /* Highlight on focus */
			}

		  .inbox h2 {
			font-size: 2rem;
			margin-bottom: 20px;
			text-align: center;
			color: #fff;
		  }

		  #conversation-list {
			list-style: none;
			padding: 0;
			margin: 0;
		  }

		  .conversation-item {
			background: #ffffff;
			padding: 15px;
			margin-bottom: 10px;
			border-radius: 8px;
			transition: background 0.3s;
		  }

		  .conversation-item a {
			text-decoration: none;
			color: #333;
			font-weight: bold;
		  }

		  .conversation-item:hover {
			background-color: #e0e0e0;
		  }


		  .message-view {
			background: rgba(255, 255, 255, 0.1);
			padding: 30px;
			margin-top: 50px;
			margin-bottom: 500px;
			border-radius: 10px;
			box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
			width: 100%;
			flex: 1;
			max-width: 600px;

		  }

		  .message-view h2 {
			font-size: 2rem;
			margin-bottom: 20px;
			text-align: center;
			color: #fff;
		  }

		  label {
			font-size: 1.1rem;
			font-weight: bold;
			display: block;
			margin-bottom: 8px;
			color: #fff;
		  }

		  input[type="email"], textarea {
			width: 100%;
			padding: 12px;
			font-size: 1rem;
			margin-bottom: 15px;
			border: 1px solid #ccc;
			border-radius: 8px;
			background: #fff;
			color: #333;
			transition: border 0.3s ease;
		  }

		  input[type="email"]:focus, textarea:focus {
			border-color: #268fff;
			box-shadow: 0 0 8px rgba(38, 143, 255, 0.5);
		  }

		  .button {
			background: linear-gradient(135deg, #6a11cb, #2575fc);
			color: white;
			font-size: 1.1rem;
			padding: 12px 25px;
			border: none;
			border-radius: 8px;
			cursor: pointer;
			width: 100%;
			transition: background 0.3s;
		  }

		  button:hover {
			background: linear-gradient(135deg, #2575fc, #6a11cb);
		  }

		  /* Small device (mobile) adjustments */
		  @media (max-width: 768px) {
			.container {
			  flex-direction: column;
			  gap: 20px;
			}

			.inbox, .message-view {
			  max-width: 100%;
			}

			.message-view {
			  padding: 20px;
			}
		  }
		</style>

</head>
<body>
  <div class="container">
    <div class="inbox">
      <h2>Inbox</h2>
      <ul id="conversation-list">
        <?php foreach ($messages as $conversation): ?>
          <li class="conversation-item">
            <a href="conversation.php?id=<?= $conversation['messageid'] ?>">
              <?= $conversation['sender'] ?>: <?= $conversation['preview'] ?>
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
		  hx-params="receiver_email">
		  
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



