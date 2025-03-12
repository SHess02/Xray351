<nav class="navbar">
    <button class="nav-btn back-btn" onclick="history.back()">&#8592;</button>
    <a href="browsingtab.php" class="nav-btn">Recent</a>
	<a href='view_all.php?type=jobs' class="nav-btn">Jobs</a>
	<a href='view_all.php?type=companies' class="nav-btn">Companies</a>
	<a href='view_all.php?type=events' class="nav-btn">Events</a>

    
    <div class="search-container">
        <input type="text" class="search-bar" placeholder="Search...">
    </div>
	
	<a href="browsingtab.php" class="nav-btn">Recent</a>

    <div class="nav-right">
        <button class="nav-btn">🔔</button>
        <button class="nav-btn profile-btn">👤</button>
    </div>
</nav>


<style>

/* Navbar Styling */
	.navbar {
		display: flex;
		align-items: center;
		justify-content: space-between;
		background-color: #007bff;
		padding: 10px 20px;
		box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
		position: fixed;
		top: 0;
		left: 0;
		width: 100%;
		z-index: 1000;
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
	}

	/* Centered Search Bar */
	.search-bar {
		width: 100%;
		max-width: 400px; /* Increased size */
		padding: 8px;
		border: none;
		border-radius: 5px;
		font-size: 16px;
	}

	/* Right-side container for Profile & Notifications */
	.nav-right {
		display: flex;
		align-items: center;
		gap: 15px; /* Increased gap to ensure buttons are not cut off */
		margin-right: 10px; /* Prevents profile button from getting cut off */
	}

	/* Profile & Notifications Buttons */
	.profile-btn {
		font-size: 20px;
	}

	/* Responsive Design */
	@media (max-width: 600px) {
		.search-bar {
			max-width: 200px;
		}
	}

</style>