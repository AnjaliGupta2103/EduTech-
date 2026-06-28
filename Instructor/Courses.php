<?php 
session_start();
include "../Utils/Util.php";
if (isset($_SESSION['username']) &&
    isset($_SESSION['instructor_id'])) {
  
    include "../Controller/Instructor/Course.php";
  
    $instructor_id = $_SESSION['instructor_id'];
    $row_count = getCountByInstructorId($instructor_id);

    $page = 1;
    $row_num = 5;
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
    $courses = getSomeCoursesByInstructorId($offset, $row_num, $instructor_id);
    # Header
    $title = "EduWave - Courses ";
    include "inc/Header.php";

?>

<div class="container">
  <!-- NavBar -->
  <?php include "inc/NavBar.php"; ?>
  
  <div class="instructor-courses-page">
    <div class="course-page-header">
      <h3>Your Courses <span>(<?=$row_count?>)</span></h3>
      <a href="Courses-add.php" class="btn btn-primary"><i class="fa fa-plus"></i> Add Course</a>
    </div>

    <?php if ($courses) { ?>
      <div class="course-list">
        <?php foreach ($courses as $course) {
          $status = $course["status"];
          $course_id = $course["course_id"];
          $text_temp = $status == "Public" ? "Private": "Public";
          $badge_class = $status == "Public" ? "success" : "danger";
        ?>
        <div class="course-card">
          <div class="course-card-badge status <?=$badge_class?>">
            <?=$status?>
          </div>
          <div class="course-card-body">
            <div class="course-card-meta">
              <span><i class="fa fa-book"></i> Course ID: <?=$course_id?></span>
            </div>
            <h5><?=htmlspecialchars($course["title"])?></h5>
            <div style="height: 20px;"></div>
            <div class="course-card-actions action_btn">
              <a href="Courses-View.php?course_id=<?=$course_id?>" class="btn btn-sm btn-outline-primary">
                <i class="fa fa-eye"></i> View
              </a>
              <a href="Courses-content-add.php?course_id=<?=$course_id?>" class="btn btn-sm btn-outline-secondary">
                <i class="fa fa-plus"></i> Add Content
              </a>
              <a href="javascript:void(0)" onclick="ChangeStatus(this, <?=$course_id?>)" class="btn btn-sm btn-warning">
                <i class="fa fa-toggle-off"></i> <?=$text_temp?>
              </a>
            </div>
          </div>
        </div>
        <?php } ?>
      </div>

      <?php if ($last_page > 1 ) { ?>
      <nav aria-label="Page navigation" class="mt-5">
        <ul class="pagination justify-content-center">
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
            <li class="page-item">
              <a href="Courses.php?page=<?=$prev?>" class="page-link">← Previous</a>
            </li>
           <?php }else { ?>
            <li class="page-item disabled">
              <span class="page-link">← Previous</span>
            </li>
           <?php 
           }
           $push_mid = $page;
           if ($page >= 2)  $push_mid = $page - 1;
           if ($page > 3)  $push_mid = $page - 3;
          
           for($i = $push_mid; $i < 5 + $page; $i++){
            if($i == $page){ ?>
            <li class="page-item active" aria-current="page">
              <span class="page-link"><?=$i?></span>
            </li>
           <?php }else{ ?>
            <li class="page-item">
              <a href="Courses.php?page=<?=$i?>" class="page-link"><?=$i?></a>
            </li>
           <?php } 
           if($last_page <= $i)break;
            } 
            if($next_btn){
            ?>
            <li class="page-item">
              <a href="Courses.php?page=<?=$next?>" class="page-link">Next →</a>
            </li>
        <?php }else { ?>
            <li class="page-item disabled">
              <span class="page-link">Next →</span>
            </li>
        <?php } ?>
        </ul>
      </nav>
      <?php } ?>
    <?php } else { ?>
      <div class="empty-state" style="margin-top: 60px;">
        <i class="fa fa-inbox"></i>
        <h5>No Courses Yet</h5>
        <p class="text-muted mb-3">Start by creating your first course</p>
        <a href="Courses-add.php" class="btn btn-primary">
          <i class="fa fa-plus"></i> Create Course
        </a>
      </div>
    <?php } ?>
  </div>
</div>

 <!-- Footer -->
<?php include "inc/Footer.php"; ?>
<script src="../assets/js/jquery-3.5.1.min.js"></script>
<script type="text/javascript">
  var valu= "";
  var btext= "";
  function ChangeStatus(current, cou_id){
    var $card = $(current).closest('.course-card');
    var cStatus = $card.find('.status').text().trim();
   
    if (cStatus == "Private") {
      valu = "Public";
      btext = "Private";
    }
    else {
      valu= "Private"; 
      btext = "Public"; 
    }

    $.post("Action/active-course.php",
    {
      course_id: cou_id,
      val: valu
    },
    function(data, status){
      if (status == "success") {
        $card.find('.status').text(valu);
        $card.find('.action_btn a:last').text(btext);
      }

    });
  }
</script>
<?php
 }else { 
$em = "First login ";
Util::redirect("../login.php", "error", $em);
} ?>
