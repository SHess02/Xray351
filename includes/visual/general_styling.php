<style>
	/* General Styles */
	body {
		font-family: Arial, sans-serif;
		background-color: #f4f4f4;
		margin: 0;
		padding-top: 80px; /* Ensure content starts below navbar */
	}

	/* Navbar Styling */
	.navbar {
		display: flex;
		align-items: center;
		justify-content: space-between;
		background-color: #007bff;
		padding: 12px 20px;
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
		max-width: 450px;
		padding: 10px;
		border: 1px solid #ddd;
		border-radius: 5px;
		font-size: 16px;
	}

	/* Right-side container for Profile & Notifications */
	.nav-right {
		display: flex;
		align-items: center;
		gap: 20px;
		margin-right: 15px;
	}

	/* Profile & Notifications Buttons */
	.profile-btn {
		font-size: 20px;
	}

	/* Lists Container */
	.lists-container {
		display: flex;
		justify-content: space-around;
		flex-wrap: wrap;
		gap: 20px;
		margin: 20px auto;
		max-width: 1000px;
	}

	/* Individual List Styling */
	.list {
		flex: 1;
		min-width: 280px;
		max-width: 320px;
		padding: 15px;
		border: 1px solid #ccc;
		border-radius: 8px;
		box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
		background-color: white;
		text-align: center;
	}

	/* Heading */
	h1 {
		text-align: center;
		font-weight: bold;
		color: #333;
		margin-bottom: 10px;
	}
	
	h3 {
		font-size: 1.3em;
		font-weight: bold;
		color: #333;
		margin-bottom: 10px;
	}

	/* Unordered List */
	ul {
		list-style-type: none;
		padding: 0;
		margin: 0;
	}

	ul li {
		margin: 8px 0;
	}

	ul li a {
		text-decoration: none;
		color: #007bff;
		font-weight: bold;
		font-size: 1.1em;
	}

	ul li a:hover {
		text-decoration: underline;
	}

	/* View All Links */
	.view-all-link {
		display: block;
		margin-top: 12px;
		font-weight: bold;
		color: #007bff;
		text-decoration: none;
	}

	.view-all-link:hover {
		text-decoration: underline;
	}

	table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f8f9fa;
        border-radius: 8px;
        overflow: hidden;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 12px;
        text-align: center;
    }

    th {
        background-color: #007bff;
        color: white;
        font-weight: bold;
        cursor: pointer;
    }

    td {
        font-size: 1.1em;
        color: #333;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tr:hover {
        background-color: #e2e6ea;
    }
	
	/* Responsive Design */
	@media (max-width: 768px) {
		.lists-container {
			flex-direction: column;
			align-items: center;
		}

		.search-bar {
			max-width: 300px;
		}

		.nav-right {
			gap: 12px;
		}
	}

</style>