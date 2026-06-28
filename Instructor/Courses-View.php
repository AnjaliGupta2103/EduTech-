<?php 
session_start();
include "../Utils/Util.php";
if (isset($_SESSION['username']) && isset($_SESSION['instructor_id'])) {
  include "../Controller/Instructor/Course.php";

  $course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;
  $chapter_id = isset($_GET['chapter_id']) ? (int)$_GET['chapter_id'] : 0;
  $topic_id = isset($_GET['topic_id']) ? (int)$_GET['topic_id'] : 0;

  $courseData = getById($course_id, $chapter_id, $topic_id);
  $course = $courseData['course'];
  $chapters = $courseData['chapters'];
  $topics = $courseData['topics'];
  $content = $courseData['content'];

  if (!$chapter_id && $chapters && $chapters[0]) {
    $chapter_id = $chapters[0]['chapter_id'];
  }
  if (!$topic_id && $topics && $topics[0]) {
    $topic_id = $topics[0]['topic_id'];
  }

  if ($chapter_id && $topics) {
    $firstTopicForChapter = null;
    foreach ($topics as $topicEntry) {
      if ((int)$topicEntry['chapter_id'] === (int)$chapter_id) {
        $firstTopicForChapter = $topicEntry;
        break;
      }
    }
    if ($firstTopicForChapter && !$topic_id) {
      $topic_id = $firstTopicForChapter['topic_id'];
    }
  }

  $selectedChapter = null;
  $selectedTopic = null;
  if ($chapters) {
    foreach ($chapters as $chapterEntry) {
      if ((int)$chapterEntry['chapter_id'] === (int)$chapter_id) {
        $selectedChapter = $chapterEntry;
        break;
      }
    }
  }
  if ($topics) {
    foreach ($topics as $topicEntry) {
      if ((int)$topicEntry['topic_id'] === (int)$topic_id) {
        $selectedTopic = $topicEntry;
        break;
      }
    }
  }
  if (!$selectedTopic && $topics) {
    $selectedTopic = $topics[0];
  }
  if (!$selectedChapter && $chapters) {
    $selectedChapter = $chapters[0];
  }

  $content = getById($course_id, $selectedChapter['chapter_id'] ?? $chapter_id, $selectedTopic['topic_id'] ?? $topic_id)['content'];

  $title = $course['title'] . " - EduWave";
  include "inc/Header.php";
?>
<div class="container">
  <?php include "inc/NavBar.php"; ?>
  <div class="side-by-side mt-5">
    <div class="l-side shadow p-3">
      <ul class="list-group">
        <?php if ($chapters) { foreach ($chapters as $chapter) { ?>
          <li class="list-group-item">
            <a href="Courses-View.php?course_id=<?=$course_id?>&chapter_id=<?=$chapter['chapter_id']?>&topic_id=0" class="btn badge-primary">
              <?=$chapter['title']?>
            </a>
            <?php
              $chapterTopics = array_filter($topics, function($topic) use ($chapter) {
                return $topic['chapter_id'] == $chapter['chapter_id'];
              });
            ?>
            <?php if ($chapterTopics) { ?>
              <ul>
                <?php foreach ($chapterTopics as $topic) { ?>
                  <li>
                    <a href="Courses-View.php?course_id=<?=$course_id?>&chapter_id=<?=$chapter['chapter_id']?>&topic_id=<?=$topic['topic_id']?>" class="btn badge-primary">
                      <?=$topic['title']?>
                    </a>
                  </li>
                <?php } ?>
              </ul>
            <?php } ?>
          </li>
        <?php } } ?>
      </ul>
    </div>

    <div class="r-side p-5 shadow">
      <a href="Course-edit.php?course_id=<?=$course_id?>" class="btn btn-primary">Update Course</a><br><br>
      <?php if ($chapters) { ?>
        <h4><?=htmlspecialchars($selectedChapter['title'] ?? '')?></h4>
        <h5><?=htmlspecialchars($selectedTopic['title'] ?? '')?></h5>
        <div>
          <?php if (!empty($content['data'])) { echo $content['data']; } else { ?>
            <div class="alert alert-info"><i class="fa fa-info-circle"></i> No content available for this topic yet.</div>
          <?php } ?>
        </div>
      <?php } else { ?>
        <div class="alert alert-info"><i class="fa fa-info-circle"></i> No chapters available for this course yet.</div>
      <?php } ?>
    </div>
  </div>
</div>

<?php include "inc/Footer.php"; ?>
<?php
} else {
  $em = "First login ";
  Util::redirect("../login.php", "error", $em);
}
?>
