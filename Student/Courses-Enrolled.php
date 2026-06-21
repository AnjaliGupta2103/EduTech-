<?php 
session_start();
include "../Utils/Util.php";
include "../Utils/Validation.php";
if (isset($_SESSION['username']) &&
    isset($_SESSION['student_id'])) {
    
   if (isset($_GET['course_id'])) {
      include "../Controller/Student/EnrolledStudent.php";
      include "../Controller/Student/Course.php";
      $course_id = Validation::clean($_GET['course_id']);
      $course_id = Validation::clean($_GET['course_id']);
      $course_id = Validation::clean($_GET['course_id']);
      
    }else{
        $em = "Invalid course id ";
        Util::redirect("../Courses.php", "error", $em);
    }

    
      $chapters = getFirstChapterByCourseId($course_id);

      $topics = getFirstTopicByCourseId($course_id);

       $_chapter_id = $chapters['chapter_id'];
       $_topic_id = $topics['topic_id'];

     if(isset($_GET['chapter_id'])) {
      $_chapter_id = Validation::clean($_GET['chapter_id']);
     }
     if(isset($_GET['topic_id'])) {
      $_topic_id = Validation::clean($_GET['topic_id']);
     }

     $psag_exes = isPageExes($course_id, $_chapter_id, $_topic_id);
     if($psag_exes == 0){
         Util::redirect("../404.php", "error", "404");
     }

    $course = getById($course_id, $_chapter_id, $_topic_id);

    $student_id = $_SESSION['student_id'];
    $data = array($course_id, $student_id);
    $res = check_enrolled_student($data);

    if ($res == 0) {
      $em = "Invalid course id ";
      Util::redirect("Courses.php", "course_id", $course_id);
    }
    $progress = getStudentProgress($course_id, $student_id);
    if ($progress >= 100) {
      $progress = 100;
    }

    $all_chapters = count($course['chapters']);
    $chapter_val = (1 / $all_chapters) * 100;


    # Header
    $title = "EduPulse - ".$course['course']['title'];
    include "inc/Header.php";
?>
<div class="container">
  <!-- NavBar & Profile-->
  <?php include "inc/NavBar.php";?>
  <div class="course-player-layout">
    <aside class="course-sidebar shadow-sm">
      <div class="course-sidebar-header">
        <div>
          <p class="eyebrow">Course Content</p>
          <h5 class="mb-0">Lessons</h5>
        </div>
        <button id="sideBtn" class="btn btn-light btn-sm"><i class="fa fa-bars"></i></button>
      </div>
      <ul class="course-sidebar-menu" id="sideMenu">
        <?php $i=0; foreach($course['chapters'] as $chapter) { $i++; 
            if ($chapter['chapter_id'] == $_chapter_id) {
                $progress_plus = ($chapter_val*$i);
                $cureent =  $chapter_val *$i;
                if ($cureent >= $progress) {
                 updateStudentProgress($course_id, $student_id, $progress_plus);
                }
            }
            
        ?>
          <li class="course-sidebar-chapter">
            <a href="javascript:void()" class="course-sidebar-chapter-title">
              <?=$chapter['title']?></a>
            <ul class="course-sidebar-topic-list">
              <?php
               foreach($course['topics'] as $topic) { 
                 if ($topic['chapter_id'] == $chapter['chapter_id']) {
                  if ($topic['topic_id'] == $_topic_id) { 
                    $chapter_title = $chapter['title'];
                    $topic_title = $topic['title'];
              ?>
               <li>
                 <a href="Courses-Enrolled.php?course_id=<?=$course_id?>&chapter_id=<?=$topic['chapter_id']?>&topic_id=<?=$topic['topic_id']?>" class="course-sidebar-topic active">
                  <b><?=$topic['title']?></b></a>
               </li>
               <?php }else { ?>
                <li>
                 <a href="Courses-Enrolled.php?course_id=<?=$course_id?>&chapter_id=<?=$topic['chapter_id']?>&topic_id=<?=$topic['topic_id']?>" class="course-sidebar-topic"><?=$topic['title']?></a>
               </li>
               <?php } } } ?>
            </ul>
          </li>
         <?php } ?>
      </ul>
    </aside>
    <main class="course-content-panel shadow-sm">
      <div class="course-content-header">
        <div>
          <p class="eyebrow">Course</p>
          <h4 class="mb-1"><?=$course['course']['title']?></h4>
          <span class="text-muted"><?=$chapter_title?> · <?=$topic_title?></span>
        </div>
        <span class="course-progress-pill"><?=ceil($progress)?>% complete</span>
      </div>
      <div class="course-content-body">
        <?php if (!empty($course['content']['data'])) {
          echo $course['content']['data'];
        }
         ?>
      </div>
      <div class="course-content-footer">
        <div>
          <h6 class="mb-2">Progress</h6>
          <div class="progress mb-2" style="height: 10px;">
            <div class="progress-bar" role="progressbar" style="width: <?=$progress?>%;" aria-valuenow="<?=$progress?>" aria-valuemin="0" aria-valuemax="100"><?=ceil($progress)?>%</div>
          </div>
        </div>
        <?php if ($progress == 100) {?>
          <a class="btn btn-success" href="Action/generateCertificate.php?course_id=<?=$course['content']['course_id']?>">Get Certificate</a>
        <?php }else { ?>
          <a class="btn btn-warning disabled" href="#">Get Certificate</a>
        <?php } ?>
      </div>
    </main>
  </div>
</div>

 <!-- Footer -->
<?php include "inc/Footer.php"; ?>
<script src="../assets/js/jquery-3.5.1.min.js"></script>

    <script>
      // Side Menu sideMenu
      $("#sideBtn").click(function(){
          $("#sideMenu").slideToggle();
      });
    </script>
<?php
 }else { 
$em = "First login ";
Util::redirect("../login.php", "error", $em);
} ?>