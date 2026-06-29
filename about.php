<!DOCTYPE html>
<html>
<head>
	<title>About - EduWave</title>
	<link rel="stylesheet" type="text/css" href="assets/css/welcome.css">
	<link rel="icon" type="image/x-icon" href="favicon.ico">
</head>
<body>
    <section class="section-1 about-p">
      	<div class="overl"></div>
      	<header>
      		<h2 class="logo">
      		  <img src="assets/img/Logo.png" alt="EduWave logo">
    	      <span>EduWave</span>
        </h2>
    		<nav>
    			<a href="index.php">Home</a>
    			<a href="about.php" class="active">About</a>
    			<a href="signup.php">Sign Up</a>
    			<a href="login.php">Login</a>
    		</nav>
      	</header>
      <div class="about-hero">
        <div class="hero-panel">
          <p class="eyebrow">About EduWave</p>
          <h1>Professional online learning for students, instructors and admins.</h1>
          <p>EduWave offers a modern, secure learning environment with focused dashboards for every role. It combines course management, enrollment, and content creation in one polished platform.</p>
        </div>
      </div>
    </section>

    <section class="section-2 about-section">
      <div class="about-content">
        <div class="about-summary">
          <h2>Who we are</h2>
          <p>EduWave is a purpose-built learning platform designed for clarity, reliability, and usability. The application helps learners find courses, enables instructors to create educational content, and gives admins tools to manage users and courses effectively.</p>
        </div>

        <div class="about-grid">
          <article class="info-card">
            <h3>Mission</h3>
            <p>Deliver a clean, accessible learning platform that removes friction from course access, content publishing, and role-based management.</p>
          </article>
          <article class="info-card">
            <h3>Vision</h3>
            <p>Make online education more professional and easier to navigate with a consistent experience for students, instructors, and administrators.</p>
          </article>
          <article class="info-card">
            <h3>Values</h3>
            <p>Clarity, usability, security, and dependable performance guide every part of EduWave’s design and implementation.</p>
          </article>
        </div>

        <div class="about-details">
          <h2>Key goals</h2>
          <ul class="goal-list">
            <li>Provide a streamlined learning experience for students.</li>
            <li>Offer instructors efficient course creation and content control.</li>
            <li>Enable admins to manage the platform confidently.</li>
            <li>Keep the interface responsive and easy to use.</li>
            <li>Ensure the site feels polished and professional.</li>
          </ul>
        </div>
      </div>
    </section>

    <footer class="main-footer">
      <h4>RCD2013C - EduWave &copy;2026</h4>
    </footer>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

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
