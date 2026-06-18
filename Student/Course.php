<?php 
session_start();
include "../Utils/Util.php";
include "../Utils/Validation.php";
if (isset($_SESSION['username']) &&
    isset($_SESSION['student_id'])) {
    
   if (isset($_GET['course_id'])) {
      include "../Controller/Student/Course.php";
      $_id = Validation::clean($_GET['course_id']);
      $course = getCourseDetails($_id); 
    }else{
        $em = "Invalid course id ";
        Util::redirect("../Courses.php", "error", $em);
    }
    if ($course == 0) {
       $em = "Invalid course id ";
        Util::redirect("Courses.php", "error", $em);
    }
    # Header
    $title = "EduPulse - Students ";
    include "inc/Header.php";

?>
<div class="container">
  <!-- NavBar -->
  <?php include "inc/NavBar.php"; ?>

  <?php
    $coverUrl = !empty($course['cover']) ? $course['cover'] : 'default_course.jpg';
    $topicCount = isset($course['topic_nums']) ? (int)$course['topic_nums'] : 0;
    $chapterCount = isset($course['chapter_nums']) ? (int)$course['chapter_nums'] : 0;
    $instructorName = !empty($course['instructor_name']) ? $course['instructor_name'] : 'Unknown Instructor';
    $createdAt = !empty($course['created_at']) ? date('M d, Y', strtotime($course['created_at'])) : 'Unknown date';
  ?>
  <div class="card course-detail-card mx-auto mb-4" style="max-width: 900px;">
    <div class="row g-0">
      <div class="col-md-5">
        <img src="../Upload/thumbnail/<?=$coverUrl?>" 
             class="img-fluid rounded-start h-100 w-100 object-fit-cover" 
             alt="<?=$course['title']?>">
      </div>
      <div class="col-md-7">
        <div class="card-body d-flex flex-column h-100">
          <h4 class="card-title mb-3"><?=htmlspecialchars($course['title'])?></h4>
          <p class="card-text text-muted mb-4"><?=nl2br(htmlspecialchars($course['description']))?></p>
          <div class="mb-3">
            <span class="d-block mb-2"><strong>Lessons:</strong> <?=$topicCount?></span>
            <span class="d-block mb-2"><strong>Chapters:</strong> <?=$chapterCount?></span>
            <span class="d-block mb-2"><strong>Instructor:</strong> <?=$instructorName?></span>
            <span class="d-block"><strong>Created:</strong> <?=$createdAt?></span>
          </div>
          <div class="mt-auto">
            <?php if ($topicCount > 0) { ?>
              <a href="Action/Courses-Enrolled.php?course_id=<?=$course['course_id']?>" class="btn btn-success me-2">Enroll in Course</a>
            <?php } else { ?>
              <button class="btn btn-secondary" disabled>Course content coming soon</button>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

 <!-- Footer -->
<?php include "inc/Footer.php"; ?>

<?php
 }else { 
$em = "First login ";
Util::redirect("../login.php", "error", $em);
} ?>