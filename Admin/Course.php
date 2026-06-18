<?php 
session_start();
include "../Utils/Util.php";
include "../Utils/Validation.php";
if (isset($_SESSION['username']) &&
    isset($_SESSION['admin_id'])) {
    
  if (isset($_GET['course_id'])) {
     include "../Controller/Admin/Course.php";
     $_id = Validation::clean($_GET['course_id']);
     $_chapter_id = 1;
     $_topic_id = 1;
     if(isset($_GET['chapter'])) {
      $_chapter_id = Validation::clean($_GET['chapter']);
     }
     if(isset($_GET['topic'])) {
      $_topic_id = Validation::clean($_GET['topic']);
     }
     $psag_exes = pageExes($_id, $_chapter_id);
     if($psag_exes == 0){
         Util::redirect("../404.php", "error", "404");
     }
     
     $course = getById($_id, $_chapter_id, $_topic_id);

     if (empty($course['course']['course_id'])) {
       $em = "Invalid course id";
       Util::redirect("courses.php", "error", $em);
     }
      $num_topic = 0;

    # Header
    $title = "EduPulse - ". $course['course']["title"];
    include "inc/Header.php";
    
?>
<div class="container-fluid">
  <!-- NavBar & Profile-->
  <?php include "inc/NavBar.php";?>
  
  <!-- Breadcrumb Navigation -->
  <div class="course-breadcrumb mt-3">
    <ol class="breadcrumb bg-light py-3 px-4 rounded">
      <li class="breadcrumb-item"><a href="Courses.php" class="text-decoration-none"><i class="fa fa-book"></i> Courses</a></li>
      <li class="breadcrumb-item active"><?=$course['course']["title"]?></li>
      <li class="breadcrumb-item active"><?=$chapter_title ?? 'Chapter'?></li>
    </ol>
  </div>

  <div class="side-by-side mt-4">
    <!-- Left Sidebar: Chapter Navigation -->
    <div class="l-side sidebar-nav p-0">
      <div class="course-nav-header">
        <h4 class="mb-0"><i class="fa fa-list"></i> Course Content</h4>
      </div>
      <div class="chapters-container p-3">
        <ul class="chapters-list">
          <?php foreach ($course['chapters'] as $chapter ) { 
            $is_active_chapter = ($chapter['chapter_id'] == $_chapter_id);
          ?>
            <li class="chapter-item <?=$is_active_chapter ? 'active' : ''?>">
              <div class="chapter-header">
                <i class="fa fa-folder-open"></i>
                <span class="chapter-title"><?=$chapter['title'] ?></span>
              </div>
              <ul class="topics-list">
                <?php foreach ($course['topics'] as $topic ) {
                  if ($chapter['chapter_id'] == $_chapter_id && $topic['chapter_id'] == $_topic_id)  $num_topic++;
                  if ($chapter['chapter_id'] == $_chapter_id) $chapter_title = $chapter['title'];
                  if ($topic['topic_id'] == $_topic_id) $topic_title = $topic['title'];
                  if ($chapter['chapter_id'] != $topic['chapter_id']) continue;
                  
                  $is_active_topic = ($topic['topic_id'] == $_topic_id);
                ?>
                  <li class="topic-item <?=$is_active_topic ? 'active' : ''?>">
                    <a href="Course.php?course_id=<?=$_id ?>&chapter=<?=$chapter['chapter_id']?>&topic=<?=$topic['topic_id']?>" class="topic-link">
                      <i class="fa fa-play-circle"></i> <?=$topic["title"]?>
                    </a>
                  </li>
                <?php } ?>
              </ul>
            </li>
          <?php } ?>
        </ul>
      </div>
    </div>

    <!-- Right Content Area -->
    <div class="r-side p-4">
      <div class="content-header mb-4">
        <h2 class="course-title mb-2"><i class="fa fa-graduation-cap"></i> <?=$course['course']["title"]?></h2>
        <div class="breadcrumb-path mb-3">
          <span class="badge bg-primary"><i class="fa fa-folder"></i> <?=$chapter_title?></span>
          <span class="mx-2">→</span>
          <span class="badge bg-success"><i class="fa fa-file"></i> <?=$topic_title?></span>
        </div>
        <hr class="my-3">
      </div>
      
      <div class="content-area">
        <?php 
        if (!empty($course['content']["data"])) {
          echo '<div class="lesson-content">' . $course['content']["data"] . '</div>'; 
        } else {
          echo '<div class="alert alert-info"><i class="fa fa-info-circle"></i> No content available for this topic yet.</div>';
        }
        ?>
      </div>
    </div>
  </div>
</div>

 <!-- Footer -->
<?php include "inc/Footer.php"; ?>

<?php
}else { 
  $em = "Invalid course id";
  Util::redirect("courses.php", "error", $em);
  }

 }else { 
$em = "First login ";
Util::redirect("../login.php", "error", $em);
} ?>