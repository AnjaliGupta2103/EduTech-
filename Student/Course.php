<?php 
session_start();
include "../Utils/Util.php";
include "../Utils/Validation.php";
if (isset($_SESSION['username']) &&
    isset($_SESSION['student_id'])) {
    
   if (isset($_GET['course_id'])) {
      include "../Controller/Student/Course.php";
      include "../Controller/Student/EnrolledStudent.php";
      $_id = Validation::clean($_GET['course_id']);
      $course = getCourseDetails($_id); 
      $student_id = $_SESSION['student_id'];
      $isEnrolled = check_enrolled_student([$course['course_id'], $student_id]);
    }else{
        $em = "Invalid course id ";
        Util::redirect("../Courses.php", "error", $em);
    }
    if ($course == 0) {
       $em = "Invalid course id ";
        Util::redirect("Courses.php", "error", $em);
    }
    # Header
    $title = "EduWave - Students ";
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
  <div class="course-detail-card mx-auto mb-4">
    <div class="row g-0">
      <div class="col-md-5">
        <img src="../Upload/thumbnail/<?=$coverUrl?>" 
             class="img-fluid h-100 w-100 object-fit-cover" 
             alt="<?=$course['title']?>">
      </div>
      <div class="col-md-7">
        <div class="course-detail-body">
          <span class="course-detail-tag">Course Overview</span>
          <h3 class="mb-3"><?=htmlspecialchars($course['title'])?></h3>
          <p class="text-muted mb-4"><?=nl2br(htmlspecialchars($course['description']))?></p>
          <div class="course-detail-stats">
            <div><strong><?=$topicCount?></strong><span>Lessons</span></div>
            <div><strong><?=$chapterCount?></strong><span>Chapters</span></div>
            <div><strong><?=$instructorName?></strong><span>Instructor</span></div>
            <div><strong><?=$createdAt?></strong><span>Created</span></div>
          </div>
          <div class="mt-auto pt-3">
            <?php if ($topicCount > 0) { ?>
              <?php if ($isEnrolled) { ?>
                <a href="Courses-Enrolled.php?course_id=<?=$course['course_id']?>" class="btn btn-primary">Continue Course</a>
              <?php } else { ?>
                <a href="Action/Courses-Enrolled.php?course_id=<?=$course['course_id']?>" class="btn btn-success">Enroll in Course</a>
              <?php } ?>
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
