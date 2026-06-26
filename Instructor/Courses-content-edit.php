<?php
session_start();
include "../Utils/Util.php";
include "../Utils/Validation.php";

if (isset($_SESSION['username']) && isset($_SESSION['instructor_id'])) {
    include "../Controller/Instructor/Course.php";

    $instructor_id = $_SESSION['instructor_id'];
    $course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;
    $chapter_id = isset($_GET['chapter_id']) ? (int)$_GET['chapter_id'] : 0;
    $topic_id = isset($_GET['topic_id']) ? (int)$_GET['topic_id'] : 0;

    $course_data = $course_id ? getById($course_id, $chapter_id, $topic_id) : [];
    $course = $course_data['course'] ?? [];
    $chapters = is_array($course_data['chapters'] ?? null) ? $course_data['chapters'] : [];
    $topics = is_array($course_data['topics'] ?? null) ? $course_data['topics'] : [];

    if (!$course || (int)($course['instructor_id'] ?? 0) !== (int)$instructor_id) {
        Util::redirect("Courses.php", "error", "Course not found");
    }

    $selected_chapter = null;
    if ($chapter_id && $chapters) {
        foreach ($chapters as $chapter) {
            if ((int)$chapter['chapter_id'] === (int)$chapter_id) {
                $selected_chapter = $chapter;
                break;
            }
        }
    }

    $selected_topic = null;
    if ($topic_id && $topics) {
        foreach ($topics as $topic) {
            if ((int)$topic['topic_id'] === (int)$topic_id) {
                $selected_topic = $topic;
                break;
            }
        }
    }

    $title = "EduPulse - Edit Content";
    include "inc/Header.php";
?>
<div class="container">
  <?php include "inc/NavBar.php"; ?>

  <div class="content-create-page">
    <section class="content-hero-card">
      <div class="hero-copy">
        <span class="hero-pill">Instructor Workspace</span>
        <h3>Edit existing course content</h3>
        <p>Select a chapter and topic to update its lesson content.</p>
      </div>
      <div class="hero-badge">Edit mode</div>
    </section>

    <div class="content-selection-card">
      <form action="Action/create-content.php" method="POST">
        <div class="form-grid-2col">
          <div class="form-group-compact">
            <label for="courseSelectTopic" class="form-label-compact">
              <i class="fa fa-book"></i> Course
            </label>
            <select class="form-select-compact" id="courseSelectTopic" name="course_id" required>
              <option value="<?=$course['course_id']?>" selected><?=htmlspecialchars($course['title'])?></option>
            </select>
          </div>

          <div class="form-group-compact">
            <label for="chapterSelect" class="form-label-compact">
              <i class="fa fa-layer-group"></i> Chapter
            </label>
            <select class="form-select-compact" id="chapterSelect" name="chapter_id" required>
              <option value="">Select a chapter</option>
              <?php foreach ($chapters as $chapter) { ?>
                <option value="<?=$chapter['chapter_id']?>" <?=((int)$chapter['chapter_id'] === (int)$chapter_id ? 'selected' : '')?>><?=htmlspecialchars($chapter['title'])?></option>
              <?php } ?>
            </select>
          </div>
        </div>

        <div class="form-group-compact">
          <label for="topicSelect" class="form-label-compact">
            <i class="fa fa-file-text"></i> Topic
          </label>
          <select class="form-select-compact" id="topicSelect" name="topic_id" required>
            <option value="">Select a topic</option>
            <?php foreach ($topics as $topic) { ?>
              <?php if ((int)$topic['chapter_id'] === (int)$chapter_id || !$chapter_id) { ?>
                <option value="<?=$topic['topic_id']?>" <?=((int)$topic['topic_id'] === (int)$topic_id ? 'selected' : '')?>><?=htmlspecialchars($topic['title'])?></option>
              <?php } ?>
            <?php } ?>
          </select>
        </div>

        <div class="form-action-row-compact">
          <button type="submit" class="btn btn-primary btn-load-content">
            <i class="fa fa-arrow-right"></i> Load Content
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="../assets/js/jquery-3.5.1.min.js"></script>
<script type="text/javascript">
  $(document).ready(function () {
    $('#chapterSelect').change(function () {
      var chapterId = $(this).val();
      if (!chapterId) {
        $('#topicSelect').html('<option value="">Select a topic</option>');
        return;
      }

      $.post('Action/load-topics.php', { chapter_id: chapterId }, function (data) {
        if (data !== 0) {
          $('#topicSelect').html(data);
        } else {
          $('#topicSelect').html('<option value="">No topics available</option>');
        }
      });
    });
  });
</script>

<?php include "inc/Footer.php"; ?>
<?php
} else {
    $em = "First login ";
    Util::redirect("../login.php", "error", $em);
}
?>
