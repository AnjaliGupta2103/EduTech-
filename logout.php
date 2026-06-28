<?php
session_start();
include "Utils/Util.php";

session_unset();
session_destroy();

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Logged Out - <?php echo isset($_GET['SITE_NAME']) ? 'EduWave' : 'System'; ?></title>
	<style>
		* {
			margin: 0;
			padding: 0;
			font-family: 'Poppins', sans-serif;
			box-sizing: border-box;
		}

		body {
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			min-height: 100vh;
			display: flex;
			align-items: center;
			justify-content: center;
			padding: 20px;
		}

		.logout-container {
			background: white;
			border-radius: 16px;
			padding: 3rem 2rem;
			text-align: center;
			max-width: 500px;
			box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
			animation: slideInUp 0.6s ease-out;
		}

		@keyframes slideInUp {
			from {
				opacity: 0;
				transform: translateY(30px);
			}
			to {
				opacity: 1;
				transform: translateY(0);
			}
		}

		.logout-icon {
			font-size: 3.5rem;
			margin-bottom: 1rem;
			animation: bounce 0.6s ease;
		}

		@keyframes bounce {
			0%, 100% { transform: translateY(0); }
			50% { transform: translateY(-10px); }
		}

		h1 {
			color: #1f3a5d;
			font-size: 2rem;
			margin-bottom: 0.5rem;
		}

		.logout-message {
			color: #6b7280;
			font-size: 1rem;
			margin-bottom: 2rem;
			line-height: 1.6;
		}

		.logout-actions {
			display: flex;
			flex-direction: column;
			gap: 1rem;
		}

		.btn {
			padding: 12px 24px;
			border: none;
			border-radius: 10px;
			font-size: 1rem;
			font-weight: 600;
			cursor: pointer;
			transition: all 0.3s ease;
			text-decoration: none;
			display: inline-block;
		}

		.btn-primary {
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			color: white;
		}

		.btn-primary:hover {
			transform: translateY(-2px);
			box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
		}

		.btn-secondary {
			background: #e5e7eb;
			color: #1f3a5d;
		}

		.btn-secondary:hover {
			background: #d1d5db;
		}

		.logout-timer {
			color: #9ca3af;
			font-size: 0.9rem;
			margin-top: 1.5rem;
		}
	</style>
</head>
<body>
<div class="logout-container">
	<div class="logout-icon">👋</div>
	<h1>See you soon!</h1>
	<p class="logout-message">
		You have been successfully logged out from your account. <br>
		Thank you for using EduWave.
	</p>
	
	<div class="logout-actions">
		<a href="login.php" class="btn btn-primary">Sign In Again</a>
		<a href="index.php" class="btn btn-secondary">Back to Home</a>
	</div>

	<p class="logout-timer">
		Redirecting to login page in <span id="countdown">5</span> seconds...
	</p>
</div>

<script>
	let countdown = 5;
	const countdownElement = document.getElementById('countdown');
	
	const interval = setInterval(() => {
		countdown--;
		countdownElement.textContent = countdown;
		
		if (countdown <= 0) {
			clearInterval(interval);
			window.location.href = 'login.php';
		}
	}, 1000);
</script>
</body>
</html>
