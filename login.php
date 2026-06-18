<?php
include "Utils/Validation.php";
include "Config.php";
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login - <?=SITE_NAME?></title>
	<link rel="stylesheet" 
	      type="text/css" 
	      href="Assets/css/login-signup.css">
	<link rel="icon" type="image/x-icon" href="favicon.ico">
</head>
<body>
<div class="login-wrapper">
	<div class="login-container">
		<!-- Header Card -->
		<div class="login-header">
			<div class="logo-section">
				<img src="assets/img/Logo.png" alt="Logo" class="logo-img">
				<h1 class="site-title"><?=SITE_NAME?></h1>
			</div>
			<p class="login-subtitle">Welcome back! Please sign in to your account</p>
		</div>

		<!-- Form Card -->
		<div class="login-card">
			<?php 
                if (isset($_GET['error'])) { ?>
                	<div class="alert alert-error">
                		<span class="alert-icon">⚠</span>
                		<?=Validation::clean($_GET['error'])?>
                	</div>
            <?php } ?>
            <?php 
                if (isset($_GET['success'])) { ?>
                	<div class="alert alert-success">
                		<span class="alert-icon">✓</span>
                		<?=Validation::clean($_GET['success'])?>
                	</div>
            <?php } ?>
			
			<form class="login-form" action="Action/login.php" method="POST">
				<div class="form-group">
					<label for="username" class="form-label">Username</label>
					<input type="text" 
					       id="username"
					       name="username"
					       class="form-input"
					       placeholder="Enter your username"
					       required>
				</div>

				<div class="form-group">
					<label for="password" class="form-label">Password</label>
					<input type="password" 
					       id="password"
					       name="password"
					       class="form-input"
					       placeholder="Enter your password"
					       required>
				</div>

				<div class="form-group">
					<label for="role" class="form-label">Sign in as</label>
					<select id="role" name="role" class="form-select" required>
						<option value="" disabled selected>Select your role</option>
						<option value="Admin">Administrator</option>
						<option value="Instructor">Instructor</option>
						<option value="Student">Student</option>
					</select>
				</div>

				<button type="submit" class="btn btn-primary btn-block">Sign In</button>
			</form>

			<div class="login-footer">
				<p class="signup-text">
					Don't have an account? 
					<a href="signup.php" class="signup-link">Create one</a>
				</p>
				<a href="index.php" class="home-link">← Back to Home</a>
			</div>
		</div>
	</div>
</div>
</body>
</html>