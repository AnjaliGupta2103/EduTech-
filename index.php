<!DOCTYPE html>
<html>
<head>
	<title>EduWave - online learning system</title>
	<link rel="stylesheet" type="text/css" href="assets/css/welcome.css">
	<link rel="icon" type="image/x-icon" href="favicon.ico">
</head>
<body>
    <section class="section-1 home-p">
     	<div class="overl"></div>
     	<header>
     		<h2 class="logo">
		  	  <img src="assets/img/Logo.png" alt="EduWave logo">
		     <span>EduWave</span>
	        </h2>
	     	<nav>
	     		<a href="index.php" class="active">Home</a>
	     		<a href="about.php">About</a>
	     		<a href="signup.php">Sign Up</a>
	     		<a href="login.php">Login</a>
	     	</nav>
     	</header>
        <div class="hero-content">
          <div class="hero-copy">
            <p class="eyebrow">Welcome to EduWave</p>
            <h1>Hi there! Start your learning journey today.</h1>
            <p class="hero-text">A simple, role-based learning system for Students, Instructors, and Admins. Discover courses, create content, and manage the platform from one shared application.</p>
          </div>
        </div>
    </section>
    <section class="section-2">
      <div class="section-intro">
        <h2>Built for every role in the learning ecosystem</h2>
        <p>EduWave connects Students, Instructors, and Admins on a single platform for course creation, enrollment, and management.</p>
      </div>
      <div class="info-cards">
        <article class="info-card">
          <h3>Goal</h3>
          <p>Provide a shared learning platform where students can enroll and learn, instructors can build courses, and admins can oversee the platform.</p>
        </article>
        <article class="info-card">
          <h3>What it does</h3>
          <p>Offers role-based dashboards, course management, enrollment workflows, content creation, and user management with session-based authentication.</p>
        </article>
      </div>
      <div class="role-cards">
        <article class="role-card">
          <h3>Student</h3>
          <p>Browse available courses, enroll instantly, and continue learning with progress tracking and course access.</p>
        </article>
        <article class="role-card">
          <h3>Instructor</h3>
          <p>Create courses, add chapters and content, and manage your teaching materials from a single dashboard.</p>
        </article>
        <article class="role-card">
          <h3>Admin</h3>
          <p>Manage users, instructors, and course visibility so the platform stays organized and secure.</p>
        </article>
      </div>
      <div class="feature-text">
        <h2>Why EduWave?</h2>
        <p>EduWave is designed for simplicity and clarity. It uses PHP, MySQL, and server-side session-based authentication to deliver a straightforward learning platform that works for all roles.</p>
      </div>
    </section>
    <footer class="main-footer">
      <h4>RCD2013C - EduWave &copy;2026</h4>
    </footer>

    <script src="assets/js/jquery-3.5.1.min.js"></script>

    <script>
    	$(document).ready(function(){
    		$(window).on('scroll', function(){
    			if ($(window).scrollTop()) {
                    $("header").addClass('bgc');
    			}else{
                    $("header").removeClass('bgc');
    			}
    		});
    	});
    </script>
</body>
</html>
