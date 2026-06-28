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

    $available_topics = [];
    if (is_array($topics) && $topics) {
        foreach ($topics as $topic) {
            if (!$chapter_id || (int)($topic['chapter_id'] ?? 0) === (int)$chapter_id) {
                $available_topics[] = $topic;
            }
        }
    }

    $selected_topic = null;
    if ($topic_id && $available_topics) {
        foreach ($available_topics as $topic) {
            if ((int)$topic['topic_id'] === (int)$topic_id) {
                $selected_topic = $topic;
                break;
            }
        }
    }

    if (!$selected_topic && $available_topics) {
        $selected_topic = $available_topics[0];
        $topic_id = (int)($selected_topic['topic_id'] ?? 0);
    }

    $course_data = $course_id ? getById($course_id, $chapter_id, $topic_id) : [];
    $content_text = '';
    if (isset($course_data['content']['data'])) {
        $content_text = $course_data['content']['data'];
    }

    $back_url = 'Course-edit.php?course_id=' . $course_id;
    $success_message = isset($_GET['success']) ? Validation::clean($_GET['success']) : '';
    $title = "EduWave - Update Content";
    include "inc/Header.php";
?>
<div class="container">
  <?php include "inc/NavBar.php"; ?>

  <div class="content-create-page">
    <?php if (!empty($success_message)) { ?>
      <div class="alert alert-success rounded-3 mb-4" role="alert">
        <i class="fa fa-check-circle"></i> <?= $success_message ?>
      </div>
    <?php } ?>

    <section class="content-hero-card">
      <div class="hero-copy">
        <span class="hero-pill">Instructor Workspace</span>
        <h3>Update your course content</h3>
        <p>The selected course, chapter, and topic are locked here so you can focus on editing the lesson content.</p>
      </div>
      <div class="hero-badge">Update flow</div>
    </section>

    <div class="content-update-card">
      <form action="Action/content-update.php" method="POST" class="content-form">
        <div class="content-selection-row">
          <div class="selection-group">
            <label class="selection-label">
              <i class="fa fa-book"></i> Course
            </label>
            <select class="selection-select is-disabled-select" id="courseSelectTopic" name="course_id" required disabled>
              <option value="<?=$course['course_id']?>" selected><?=htmlspecialchars($course['title'])?></option>
            </select>
            <input type="hidden" name="course_id" value="<?=$course_id?>" />
          </div>

          <div class="selection-group">
            <label class="selection-label">
              <i class="fa fa-layer-group"></i> Chapter
            </label>
            <select class="selection-select is-disabled-select" id="chapterSelect" name="chapter_id" required disabled>
              <option value="<?=$selected_chapter['chapter_id'] ?? ''?>" selected><?=htmlspecialchars($selected_chapter['title'] ?? '')?></option>
            </select>
            <input type="hidden" name="chapter_id" value="<?=$chapter_id?>" />
          </div>

          <div class="selection-group">
            <label class="selection-label">
              <i class="fa fa-file-text"></i> Topic
            </label>
            <select class="selection-select is-disabled-select" id="topicSelect" name="topic_id" required disabled>
              <?php if ($available_topics) { ?>
                <?php foreach ($available_topics as $topic) { ?>
                  <option value="<?=$topic['topic_id']?>" <?=((int)($topic['topic_id'] ?? 0) === (int)($selected_topic['topic_id'] ?? 0) ? 'selected' : '')?>><?=htmlspecialchars($topic['title'])?></option>
                <?php } ?>
              <?php } else { ?>
                <option value="" selected>No topics available</option>
              <?php } ?>
            </select>
            <input type="hidden" name="topic_id" value="<?=$topic_id?>" />
          </div>
        </div>

        <div class="editor-section">
          <label for="contentEditor" class="editor-label">
            <i class="fa fa-edit"></i> Course Content
          </label>
          <textarea class="form-control text" name="text" id="contentEditor"><?=htmlspecialchars($content_text, ENT_QUOTES, 'UTF-8')?></textarea>
        </div>

        <div class="editor-actions">
          <a href="<?=$back_url?>" class="btn btn-outline-secondary btn-back-content">
            <i class="fa fa-arrow-left"></i> Back
          </a>
          <button type="submit" class="btn btn-primary btn-save-content">
            <i class="fa fa-check"></i> Update Content
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript">
  var initialEditorContent = <?= json_encode($content_text, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) ?>;

  function syncRichTextEditor() {
    var editor = $('.text');
    var richEditor = editor.closest('.richText').find('.richText-editor');
    if (richEditor.length) {
      var html = richEditor.html();
      editor.val(html);
      editor.siblings('.richText-initial').val(html);
    }
  }

  $(document).ready(function() {
    $('.text').richText({
      height: 360,
      placeholder: 'Write your lesson content here...',
      bold: true,
      italic: true,
      underline: true,
      leftAlign: true,
      centerAlign: true,
      rightAlign: true,
      justify: true,
      ol: true,
      ul: true,
      heading: true,
      fontSize: true,
      fontColor: true,
      removeStyles: true,
      code: true,
      undoRedo: true,
      urls: true,
      table: true
    });

    if (initialEditorContent) {
      setTimeout(function () {
        $('.text').val(initialEditorContent);
        $('.text').closest('.richText').find('.richText-editor').html(initialEditorContent);
        $('.text').siblings('.richText-initial').val(initialEditorContent);
      }, 50);
    }

    $('.content-form').on('submit', function(e) {
      syncRichTextEditor();
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

