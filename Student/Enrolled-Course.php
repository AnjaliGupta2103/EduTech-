<?php 
session_start();
include "../Utils/Util.php";
if (isset($_SESSION['username']) &&
    isset($_SESSION['student_id'])) {

    include "../Controller/Student/Course.php";
    include "../Controller/Student/EnrolledStudent.php";
    
    $student_id = $_SESSION['student_id'];
    $courses = getEnrolledCourses($student_id);
    $row_count =  $courses[0]['count'];

    # Header
    $title = "EduWave - Students ";
    include "inc/Header.php";
    
?>
<div class="container">
  <!-- NavBar -->
  <?php include "inc/NavBar.php"; ?>

  <?php if ($row_count > 0) { ?>
  <section class="course-page-header">
    <div>
      <p class="eyebrow">My Learning</p>
      <h3>Enrolled Courses <span>(<?=$row_count?>)</span></h3>
    </div>
    <a href="Courses.php" class="btn btn-outline-primary">Browse All Courses</a>
  </section>
  <div class="course-list">

    <?php 
      for ($i=1; $i <= $row_count; $i++) { ?>
    
    <div class="course-card shadow-sm">
      <div class="course-card-image-wrap">
        <img src="../Upload/thumbnail/<?=$courses[$i]["cover"]?>" alt="<?=$courses[$i]["title"]?>">
        <span class="course-card-badge success">Enrolled</span>
      </div>
      <div class="course-card-body">
        <div class="course-card-meta">
          <span><i class="fa fa-calendar"></i> <?=$courses[$i]["created_at"]?></span>
          <span><i class="fa fa-check-circle"></i> In Progress</span>
        </div>
        <h5><?=$courses[$i]["title"]?></h5>
        <p><?=$courses[$i]["description"]?></p>
        <div class="course-card-actions">
          <a href="Courses-Enrolled.php?course_id=<?=$courses[$i]["course_id"]?>" class="btn btn-primary btn-sm">Continue</a>
        </div>
      </div>
    </div>
  <?php } ?>
  </div>
<?php }else{ ?>
  <div class="alert alert-info" role="alert">
      0 courses record found in the database
   </div>
<?php } ?>
</div>

 <!-- Footer -->
<?php include "inc/Footer.php"; ?>

<?php
 }else { 
$em = "First login ";
Util::redirect("../login.php", "error", $em);
} ?>
