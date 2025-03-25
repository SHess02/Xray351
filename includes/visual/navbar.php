<nav class="navbar">
    <div class="nav-left">
        <button class="nav-btn back-btn" onclick="history.back()">&#8592;</button>
        <a href="browsingtab.php" class="nav-btn">Recent</a>
        <a href='view_all.php?type=jobs' class="nav-btn">Jobs</a>
        <a href='view_all.php?type=companies' class="nav-btn">Companies</a>
        <a href='view_all.php?type=events' class="nav-btn">Events</a>
    </div>

    <div class="search-container">
    <form action="../includes/functional/search_results.php" method="GET" style="display: flex; width: 100%;">
        <input type="text" class="search-bar" name="query" placeholder="Search for companies, jobs, or events..." required>
        <button type="submit" class="nav-btn">🔍</button>
    </form>
	</div>


    <div class="nav-right">
		<a href="../SH_folder/inbox.php">
			<button class="nav-btn">📧</button>
		</a>
        <button class="nav-btn">🔔</button>
        <div class="profile-dropdown">
            <button class="nav-btn profile-btn">👤</button>
            <div class="dropdown-content">
                <a href="../EthanWork/profile.php">View Profile</a>
                <a href="settings.php">Settings</a>
                <a href="../SH_folder/logout.php">Logout</a>
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
}

/* Left and Right Sections */
.nav-left, .nav-right {
    display: flex;
    align-items: center;
}

/* Add spacing to prevent profile button from getting cut off */
.nav-right {
    margin-right: 15px; /* Ensures profile button is not off-screen */
    padding-right: 10px; /* Extra padding for safety */
}

/* Buttons */
.nav-btn {
    background: none;
    border: none;
    color: white;
    font-size: 18px;
    font-weight: bold;
    padding: 8px 12px;
    cursor: pointer;
    transition: background 0.3s;
}

.nav-btn:hover {
    background: rgba(255, 255, 255, 0.2);
    border-radius: 5px;
}

/* Back button styling */
.back-btn {
    font-size: 20px;
}

/* Search Bar Container */
.search-container {
    flex-grow: 1;
    display: flex;
    justify-content: center;
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    width: 100%;
    max-width: 400px;
}

/* Centered Search Bar */
.search-bar {
    width: 100%;
    padding: 8px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
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
        max-width: 200px;
    }
}
</style>
