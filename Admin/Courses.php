<?php 
session_start();
include "../Utils/Util.php";
if (isset($_SESSION['username']) &&
    isset($_SESSION['admin_id'])) {
    include "../Controller/Admin/Course.php";
    $row_count = getCount();

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
    $courses = getSomeCourses($offset, $row_num);
    # Header
    $title = "EduPulse - Courses ";
    include "inc/Header.php";

?>

<div class="container">
  <!-- NavBar -->
  <?php include "inc/NavBar.php"; ?>
  
  <div class="list-table pt-5 course-admin-page">
  <?php if ($courses) { ?>
  <div class="course-page-header">
    <div>
      <h4>All Courses</h4>
      <p class="text-muted">Showing <?=$row_count?> courses for the admin dashboard.</p>
    </div>
    <div class="d-flex gap-2 align-items-center">
      <span class="badge bg-primary py-2 px-3">Total <?=$row_count?></span>
    </div>
  </div>

  <div class="course-list admin-course-list">
    <?php foreach ($courses as $course) {
      $status = $course["status"];
      $course_id = $course["course_id"];
      $text_temp = $status == "Public" ? "Private": "Public";
      $description = !empty($course["description"]) ? $course["description"] : "View course content and chapters.";
    ?>
      <div class="course-card">
        <div class="course-card-body">
          <div class="course-card-meta">
            <span class="course-card-badge <?= $status === 'Public' ? 'success' : '' ?>"><?= htmlspecialchars($status) ?></span>
            <span class="text-muted">ID #<?= htmlspecialchars($course_id) ?></span>
          </div>
          <h5><a href="Course.php?course_id=<?= htmlspecialchars($course_id) ?>" class="text-decoration-none text-dark"><?= htmlspecialchars($course["title"]) ?></a></h5>
          <p><?= htmlspecialchars(strlen($description) > 110 ? substr($description, 0, 110) . '...' : $description) ?></p>
          <div class="course-card-actions">
            <a href="Course.php?course_id=<?= htmlspecialchars($course_id) ?>" class="btn btn-sm btn-primary">View Content</a>
            <button type="button" onclick="ChangeStatus(this, <?= htmlspecialchars($course_id) ?>)" class="btn btn-sm btn-outline-warning"><?= htmlspecialchars($text_temp) ?></button>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>

  <?php if ($last_page > 1 ) { ?>
  <div class="d-flex justify-content-center flex-wrap gap-2 mt-4 pagination-wrapper">
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
            <a href="Courses.php?page=<?=$prev?>" class="btn btn-secondary">Prev</a>
           <?php }else { ?>
            <a href="#" class="btn btn-secondary disabled">Prev</a>
           <?php 
           }
           $push_mid = $page;
           if ($page >= 2)  $push_mid = $page - 1;
           if ($page > 3)  $push_mid = $page - 3;
          
           for($i = $push_mid; $i < 5 + $page; $i++){
            if($i == $page){ ?>
             <a href="Courses.php?page=<?=$i?>" class="btn btn-success"><?=$i?></a>
           <?php }else{ ?>
             <a href="Courses.php?page=<?=$i?>" class="btn btn-secondary"><?=$i?></a>

           <?php } 
           if($last_page <= $i)break;

            } 
            if($next_btn){
            ?>
            <a href="Courses.php?page=<?=$next?>" class="btn btn-secondary">Next</a>
        <?php }else { ?>
           <a href="#" class="btn btn-secondary disabled">Next</a>
        <?php } ?>
  </div>

  <?php } ?>
  <?php } else { ?>
    <div class="alert alert-info" role="alert">
      No course records found in the database.
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
    var cStatus = $(current).parent().parent().children(".status").text().toString();
   
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
        $(current).parent().parent().children(".status").text(valu);
        $(current).parent().parent().children(".action_btn").children("a").text(btext);
       
      }

    });
  }
</script>
<?php
 }else { 
$em = "First login ";
Util::redirect("../login.php", "error", $em);
} ?>