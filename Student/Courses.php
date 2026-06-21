<?php 
session_start();
include "../Utils/Util.php";
if (isset($_SESSION['username']) &&
    isset($_SESSION['student_id'])) {

    include "../Controller/Student/Course.php";
    include "../Controller/Student/EnrolledStudent.php";
    $row_count = getCount();
    
    $page = 1;
    $row_num = 6;
    $offset = 0;
    $last_page = ceil($row_count / $row_num);
    if(isset($_GET['page'])){
    if($_GET['page'] > $last_page){
        $page = $last_page;
    }else if($_GET['page'] <= 0){
        $page = 1; 
    }else $page = $_GET['page'];
    }
    if($page != 1) $offset = ($page-1) * $row_num;
    $courses = getSomeCourses($offset, $row_num);

    # Header
    $title = "EduPulse - Students ";
    include "inc/Header.php";

?>
<div class="container">
  <!-- NavBar -->
  <?php include "inc/NavBar.php"; ?>
  <?php if ($courses) { ?>
  <section class="course-page-header">
    <div>
      <p class="eyebrow">Learning Hub</p>
      <h3>All Courses <span>(<?=$row_count?>)</span></h3>
    </div>
    <a href="Enrolled-Course.php" class="btn btn-outline-primary">My Enrolled Courses</a>
  </section>
  <div class="course-list">

    <?php foreach ($courses as $course) {?> 
    
    <?php
      $coverUrl = !empty($course["cover"]) ? $course["cover"] : "default_course.jpg";
      $courseDescription = strlen($course["description"]) > 150 ? substr($course["description"], 0, 150) . "..." : $course["description"];
      $createdAt = !empty($course["created_at"]) ? date("M d, Y", strtotime($course["created_at"])) : "Unknown date";
      $isEnrolled = check_enrolled_student([$course["course_id"], $_SESSION['student_id']]);
    ?>
    <div class="course-card shadow-sm">
      <div class="course-card-image-wrap">
        <img src="../Upload/thumbnail/<?=$coverUrl?>" alt="<?=$course["title"]?>">
        <span class="course-card-badge">Popular</span>
      </div>
      <div class="course-card-body">
        <div class="course-card-meta">
          <span><i class="fa fa-calendar"></i> <?=$createdAt?></span>
          <span><i class="fa fa-clock-o"></i> Self-paced</span>
        </div>
        <h5><?=$course["title"]?></h5>
        <p><?=$courseDescription?></p>
        <div class="course-card-actions">
          <a href="Course.php?course_id=<?=$course["course_id"]?>" class="btn btn-primary btn-sm">View Course</a>
          <?php if ($isEnrolled) { ?>
            <a href="Courses-Enrolled.php?course_id=<?=$course["course_id"]?>" class="btn btn-success btn-sm">Continue</a>
          <?php } else { ?>
            <a href="Action/Courses-Enrolled.php?course_id=<?=$course["course_id"]?>" class="btn btn-outline-secondary btn-sm">Enroll</a>
          <?php } ?>
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
  <?php if ($last_page > 1 ) { ?>
  <div class="d-flex justify-content-center mt-3 border">
      <?php
            $prev = 1;
            $next = 1;
            $next_btn = true;
            $prev_btn = true;
            if($page <= 1) $prev_btn = false; 
            if($last_page ==  $page) $next_btn = false; 
            if($page > 1) $prev = $page - 1;
            if($page < $last_page) $next = $page + 1;
            
            if ($prev_btn){
            ?>
            <a href="Courses.php?page=<?=$prev?>" class="btn btn-secondary m-2">Prev</a>
           <?php }else { ?>
            <a href="#" class="btn btn-secondary m-2 disabled">Prev</a>
            
           <?php 
           }
           $push_mid = $page;
           if ($page >= 2)  $push_mid = $page - 1;
           if ($page > 3)  $push_mid = $page - 3;
          
           for($i = $push_mid; $i < 5 + $page; $i++){
            if($i == $page){ ?>
             <a href="Courses.php?page=<?=$i?>" class="btn btn-success m-2"><?=$i?></a>
           <?php }else{ ?>
             <a href="Courses.php?page=<?=$i?>" class="btn btn-secondary m-2"><?=$i?></a>

           <?php } 
           if($last_page <= $i)break;

            } 
            if($next_btn){
            ?>
            <a href="Courses.php?page=<?=$next?>" class="btn btn-secondary m-2">Next</a>
        <?php }else { ?>
           <a href="#" class="btn btn-secondary m-2 disabled" des>Next</a>
        <?php } ?>
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