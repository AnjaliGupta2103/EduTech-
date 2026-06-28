<?php 
session_start();
include "../Utils/Util.php";
include "../Utils/Validation.php";
if (isset($_SESSION['username']) &&
    isset($_SESSION['admin_id'])) {
    
  if (isset($_GET['course_id'])) {
     include "../Controller/Admin/Course.php";
     $_id = Validation::clean($_GET['course_id']);
     $_chapter_id = isset($_GET['chapter']) ? Validation::clean($_GET['chapter']) : 1;
     $_topic_id = isset($_GET['topic']) ? Validation::clean($_GET['topic']) : 1;

     $course = getById($_id, $_chapter_id, $_topic_id);

     if (empty($course['course']['course_id'])) {
       $em = "Invalid course id";
       Util::redirect("courses.php", "error", $em);
     }

     $chapterIds = [];
     if (!empty($course['chapters']) && is_array($course['chapters'])) {
         $chapterIds = array_column($course['chapters'], 'chapter_id');
     }

     if (!in_array($_chapter_id, $chapterIds, true)) {
         $_chapter_id = !empty($chapterIds) ? $chapterIds[0] : 1;
     }

     $chapterTopics = [];
     if (!empty($course['topics']) && is_array($course['topics'])) {
         foreach ($course['topics'] as $topic) {
             if ($topic['chapter_id'] == $_chapter_id) {
                 $chapterTopics[] = $topic;
             }
         }
     }

     if (empty($chapterTopics) && !empty($course['topics']) && is_array($course['topics'])) {
         $chapterTopics = $course['topics'];
     }

     if (!empty($chapterTopics)) {
         $validTopicIds = array_column($chapterTopics, 'topic_id');
         if (!in_array($_topic_id, $validTopicIds, true)) {
             $_topic_id = $chapterTopics[0]['topic_id'];
         }
     } else {
         $_topic_id = 0;
     }

     $course = getById($_id, $_chapter_id, $_topic_id);
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

  <div class="course-detail-page">
    <div class="course-page-header">
      <div>
        <h3><i class="fa fa-graduation-cap"></i> <?= htmlspecialchars($course['course']["title"]) ?></h3>
        <p class="text-muted mb-0">Browse chapters and lessons for this course in a cleaner, more readable content layout.</p>
      </div>
      <div class="course-header-badges">
        <span class="badge bg-primary">Course ID <?= htmlspecialchars($course['course']["course_id"]) ?></span>
        <?php if (!empty($course['course']["status"])) { ?>
          <span class="badge bg-success"><?= htmlspecialchars($course['course']["status"]) ?></span>
        <?php } ?>
      </div>
    </div>

    <div class="side-by-side mt-4">
      <!-- Left Sidebar: Chapter Navigation -->
      <div class="course-sidebar p-0">
        <div class="course-sidebar-header">
          <h4 class="mb-0"><i class="fa fa-list"></i> Course Content</h4>
        </div>
        <div class="chapters-container p-3">
          <ul class="course-sidebar-menu">
            <?php foreach ($course['chapters'] as $chapter ) {
              $is_active_chapter = ($chapter['chapter_id'] == $_chapter_id);
            ?>
              <li class="course-sidebar-chapter">
                <span class="course-sidebar-chapter-title<?= $is_active_chapter ? ' active' : '' ?>">
                  <i class="fa fa-folder-open me-2"></i> <?= htmlspecialchars($chapter['title']) ?>
                </span>
                <ul class="course-sidebar-topic-list">
                  <?php foreach ($course['topics'] as $topic ) {
                    if ($chapter['chapter_id'] == $_chapter_id && $topic['chapter_id'] == $_topic_id)  $num_topic++;
                    if ($chapter['chapter_id'] == $_chapter_id) $chapter_title = $chapter['title'];
                    if ($topic['topic_id'] == $_topic_id) $topic_title = $topic['title'];
                    if ($chapter['chapter_id'] != $topic['chapter_id']) continue;
                    
                    $is_active_topic = ($topic['topic_id'] == $_topic_id);
                  ?>
                    <li>
                      <a href="Course.php?course_id=<?= htmlspecialchars($_id) ?>&chapter=<?= htmlspecialchars($chapter['chapter_id']) ?>&topic=<?= htmlspecialchars($topic['topic_id']) ?>" class="course-sidebar-topic<?= $is_active_topic ? ' active' : '' ?>">
                        <i class="fa fa-play-circle me-2"></i> <?= htmlspecialchars($topic["title"]) ?>
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
      <div class="r-side course-content-panel p-4">
        <div class="course-detail-card mb-4">
          <div class="course-detail-body">
            <?php if (!empty($course['course']["description"])) { ?>
              <span class="course-detail-tag">Course overview</span>
              <p class="course-description mb-3"><?= nl2br(htmlspecialchars($course['course']["description"])) ?></p>
            <?php } ?>
            <div class="course-detail-stats">
              <div>
                <strong>Chapter</strong>
                <span><?= htmlspecialchars($chapter_title ?? 'N/A') ?></span>
              </div>
              <div>
                <strong>Lesson</strong>
                <span><?= htmlspecialchars($topic_title ?? 'N/A') ?></span>
              </div>
            </div>
          </div>
        </div>

        <div class="course-detail-card">
          <div class="course-detail-body course-content-body">
            <div class="course-content-header mb-4">
              <div>
                <h4 class="course-title mb-2"><i class="fa fa-book-open"></i> Lesson Content</h4>
                <p class="text-muted mb-0">Read the active lesson and navigate through course chapters.</p>
              </div>
              <div class="course-progress-pill">
                <i class="fa fa-check-circle me-1"></i> <?= htmlspecialchars($chapter_title ?? 'Chapter') ?> → <?= htmlspecialchars($topic_title ?? 'Topic') ?>
              </div>
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