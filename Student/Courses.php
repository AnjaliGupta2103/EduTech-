<?php 
session_start();
include "../Utils/Util.php";
if (isset($_SESSION['username']) &&
    isset($_SESSION['student_id'])) {

    include "../Controller/Student/Course.php";
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
  <h4 class="course-list-title">All Courses (<?=$row_count?>)</h4>
  <div class="course-list">

    <?php foreach ($courses as $course) {?>
    
    <?php
      $coverUrl = !empty($course["cover"]) ? $course["cover"] : "default_course.jpg";
      $courseDescription = strlen($course["description"]) > 150 ? substr($course["description"], 0, 150) . "..." : $course["description"];
      $createdAt = !empty($course["created_at"]) ? date("M d, Y", strtotime($course["created_at"])) : "Unknown date";
    ?>
    <div class="card mb-4 shadow-sm course-card">
      <div class="row g-0 align-items-center">
        <div class="col-md-4">
          <img src="../Upload/thumbnail/<?=$coverUrl?>" 
               class="img-fluid rounded-start h-100 w-100 object-fit-cover" 
               alt="<?=$course["title"]?>">
        </div>
        <div class="col-md-8">
          <div class="card-body d-flex flex-column h-100">
            <h5 class="card-title mb-2"><?=$course["title"]?></h5>
            <p class="card-text text-muted mb-3"><?=$courseDescription?></p>
            <div class="mb-3 small text-secondary">
              <span class="me-3">Created: <?=$createdAt?></span>
            </div>
            <div class="mt-auto d-flex gap-2">
              <a href="Course.php?course_id=<?=$course["course_id"]?>" class="btn btn-primary btn-sm">View Course</a>
              <a href="Course.php?course_id=<?=$course["course_id"]?>" class="btn btn-outline-secondary btn-sm">Enroll</a>
            </div>
          </div>
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