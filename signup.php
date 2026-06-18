<?php 
include "Utils/Validation.php";
include "Config.php";

$fname = $uname = $email = $bd = $lname = "";
if (isset($_GET["fname"])) {
	$fname = Validation::clean($_GET["fname"]);
}
if (isset($_GET["uname"])) {
	$uname = Validation::clean($_GET["uname"]);
}
if (isset($_GET["email"])) {
	$email = Validation::clean($_GET["email"]);
}
if (isset($_GET["bd"])) {
	$bd = Validation::clean($_GET["bd"]);
}
if (isset($_GET["lname"])) {
	$lname = Validation::clean($_GET["lname"]);
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Sign Up - <?=SITE_NAME?></title>
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
			<p class="login-subtitle">Create your account and start learning today</p>
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
			
			<form class="login-form" action="Action/signup.php" method="POST">
				<div class="form-group">
					<label for="fname" class="form-label">First Name</label>
					<input type="text" 
					       id="fname"
					       name="fname"
					       class="form-input"
					       placeholder="Enter your first name"
					       value="<?=$fname?>"
					       required>
				</div>

				<div class="form-group">
					<label for="lname" class="form-label">Last Name</label>
					<input type="text" 
					       id="lname"
					       name="lname"
					       class="form-input"
					       placeholder="Enter your last name"
					       value="<?=$lname?>"
					       required>
				</div>

				<div class="form-group">
					<label for="email" class="form-label">Email Address</label>
					<input type="email" 
					       id="email"
					       name="email"
					       class="form-input"
					       placeholder="Enter your email"
					       value="<?=$email?>"
					       required>
				</div>

				<div class="form-group">
					<label for="dob" class="form-label">Date of Birth</label>
					<input type="date" 
					       id="dob"
					       name="date_of_birth"
					       class="form-input"
					       value="<?=$bd?>"
					       required>
				</div>

				<div class="form-group">
					<label for="username" class="form-label">Username</label>
					<input type="text" 
					       id="username"
					       name="username"
					       class="form-input"
					       placeholder="Choose a username (5-20 characters)"
					       value="<?=$uname?>"
					       required>
					<small class="form-hint">Must start with a letter, contain letters and numbers</small>
				</div>

				<div class="form-group">
					<label for="password" class="form-label">Password</label>
					<input type="password" 
					       id="password"
					       name="password"
					       class="form-input"
					       placeholder="Create a strong password"
					       required>
					<small class="form-hint">Use a mix of letters, numbers, and symbols</small>
				</div>

				<div class="form-group">
					<label for="re_password" class="form-label">Confirm Password</label>
					<input type="password" 
					       id="re_password"
					       name="re_password"
					       class="form-input"
					       placeholder="Re-enter your password"
					       required>
				</div>

				<button type="submit" class="btn btn-primary btn-block">Create Account</button>
			</form>

			<div class="login-footer">
				<p class="signup-text">
					Already have an account? 
					<a href="login.php" class="signup-link">Sign In</a>
				</p>
				<a href="index.php" class="home-link">← Back to Home</a>
			</div>
		</div>
	</div>
</div>
</body>
</html>