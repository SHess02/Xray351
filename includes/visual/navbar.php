<?php
$baseURL = "http://" . $_SERVER['HTTP_HOST'] . "/Xray351";
?>

<nav class="navbar">
    <div class="nav-left">
        <button class="nav-btn back-btn" onclick="history.back()">&#8592;</button>

		<a href="<?= $baseURL ?>/MasonFolder/browsingtab.php" class="nav-btn">Recent</a>
		<a href="<?= $baseURL ?>/MasonFolder/view_all.php?type=jobs" class="nav-btn">Jobs</a>
		<a href="<?= $baseURL ?>/MasonFolder/view_all.php?type=companies" class="nav-btn">Companies</a>
		<a href="<?= $baseURL ?>/MasonFolder/view_all.php?type=events" class="nav-btn">Events</a>
    </div>

    <div class="search-container">
	<form action="<?= $baseURL ?>/includes/functional/search_results.php" method="GET">
    <input type="text" name="query" placeholder="Search..." required>
    <select name="category">
        <option value="jobs">Jobs</option>
        <option value="companies">Companies</option>
        <option value="events">Events</option>
        <option value="users">Users</option>
    </select>
    <button type="submit">🔍</button>
	</form>
	</div>
	

    <div class="nav-right">
		<a href="<?= $baseURL ?>/SH_folder/inbox.php">
			<button class="nav-btn">📧</button>
		</a>
        <button class="nav-btn">🔔</button>
        <div class="profile-dropdown">
            <button class="nav-btn profile-btn">👤</button>
            <div class="dropdown-content">
                <a href="<?= $baseURL ?>/EthanWork/profile.php?userid=<?= $_SESSION['userid'] ?>">View Profile</a>
				<a href="<?= $baseURL ?>/MasonFolder/favorites.php">Favorites</a>
                <a href="<?= $baseURL ?>/SH_folder/logout.php">Logout</a>
            </div>
        </div>
    </div>
</nav>

<style>
/* Navbar Styling */
.navbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: linear-gradient(135deg, #6a11cb, #2575fc);
    color: #fff;
    padding: 10px 20px;
    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    z-index: 1000;
    height: 60px; /* Explicitly set navbar height */
}

/* Search Bar Container */
.search-container {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    max-width: 600px;
    background-color: rgba(255, 255, 255, 0.2);
    border-radius: 35px;
    padding: 5px 10px;
    box-sizing: border-box;
	margin: 0 auto;
	height: 50px;
	padding-top: 25px;
}

/* Search Bar Form Styling */
.search-container form {
    display: flex;
    width: 100%;
    background-color: transparent;
    border-radius: 25px;
    padding: 0; /* Remove padding around the form */
    box-sizing: border-box;
    justify-content: space-between;
	align-items: center;
}

/* Centered Search Bar */
.search-container input[type="text"] {
    width: 70%;
    padding: 6px 12px;
    border: none;
    border-radius: 25px;
    font-size: 14px;
    color: #333;
    margin: 0; /* Remove margin to prevent extra space */
    height: 30px; /* Explicit height to ensure vertical centering */
    box-sizing: border-box; /* Ensure padding is included in width */
}

.search-container select {
    background-color: #fff;
    border: none;
    color: #333;
    font-size: 14px;
    margin-left: 10px;
    padding: 6px 12px;
    border-radius: 25px;
    height: 30px; /* Make the select input the same height as the search bar */
    box-sizing: border-box;
}

.search-container button {
    background-color: transparent;
    border: none;
    color: white;
    cursor: pointer;
    font-size: 20px;
    margin-left: 10px;
    padding: 6px 12px;
    border-radius: 25px;
    height: 30px; /* Match the button height with the inputs */
}


/* Profile Dropdown */
.profile-dropdown {
    position: relative;
    display: inline-block;
}

/* Profile Dropdown Content */
.dropdown-content {
    display: none;
    position: absolute;
    right: 0;
    background-color: white;
    min-width: 150px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    border-radius: 5px;
    overflow: hidden;
    z-index: 1000;
}

.dropdown-content a {
    display: block;
    padding: 10px;
    text-decoration: none;
    color: black;
    font-size: 16px;
}

.dropdown-content a:hover {
    background-color: #f1f1f1;
}

/* Show dropdown on hover */
.profile-dropdown:hover .dropdown-content {
    display: block;
}

/* Responsive Design */
@media (max-width: 600px) {
    .search-container {
        max-width: 300px; /* Reduced max-width for small screens */
    }

    .nav-btn {
        font-size: 16px;
    }
}


</style>
