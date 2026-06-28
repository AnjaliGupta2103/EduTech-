
<?php 
session_start();
include "../Utils/Util.php";
include "../Utils/Validation.php";
if (isset($_SESSION['username']) &&
    isset($_SESSION['instructor_id'])) {
    include "../Controller/Instructor/Course.php";

    $instructor_id = $_SESSION['instructor_id'];
    if (!isset($_SESSION['content'])) {
        Util::redirect("Courses-content-add.php", "error", "");
    }
    $content = $_SESSION['content'];
    $content_array = explode(",", $content);
    $success_message = "";
    if (isset($_GET['success'])) {
        $success_message = Validation::clean($_GET['success']);
    }
    $cou_id = $content_array[3];
    $chapter_id = $content_array[2];
    $topic_id = $content_array[1];
    $content_id = $content_array[0];

    # Header
    $title = "EduWave - Create Course ";
    include "inc/Header.php";
    $course = getById($cou_id, $chapter_id, $topic_id);
        // $data = array('content'  => $content,
        //           'course' => $course_data,
        //           'chapters' => $chapters,
        //           'topics'   => $topics );
    foreach ($course['chapters'] as $key => $value) {
        if ($value['chapter_id'] == $chapter_id) {
            $chapter = $value;
        }
    }
    foreach ($course['topics'] as $key => $value) {
        if ($value['topic_id'] == $topic_id) {
            $topic = $value;
        }
    }

   // $chapter = in_array($course['chapters'], haystack);

// print_r($course['chapters']);
// ?>

<div class="container">
  <!-- NavBar -->
  <?php include "inc/NavBar.php"; ?>

  <div class="content-create-page">
    <?php if (!empty($success_message)) { ?>
    <div class="alert alert-success" style="margin-bottom: 20px; padding: 12px 16px; border-radius: 8px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb;">
      <i class="fa fa-check-circle"></i> <?= $success_message ?>
    </div>
    <?php } ?>
    <section class="content-hero-card">
      <div class="hero-copy">
        <span class="hero-pill">Instructor Workspace</span>
        <h3>Edit your course content</h3>
        <p>Update the selected topic with fresh lessons and materials.</p>
      </div>
      <div class="hero-badge">Live editor</div>
    </section>

    <div class="content-update-card">
      <form action="Action/content-update.php" method="POST" class="content-form">
        <!-- Selection Row -->
        <div class="content-selection-row">
          <div class="selection-group">
            <label class="selection-label"><i class="fa fa-book"></i> Course</label>
            <select class="selection-select" id="courseSelectTopic" name="course_id" required tabindex="-1" style="pointer-events:none; opacity:0.7; background-color:#f8f9fa;">
               <option value="<?=$course['course']['course_id']?>"><?=$course['course']['title']?></option>
            </select>
          </div>

          <div class="selection-group">
            <label class="selection-label"><i class="fa fa-layer-group"></i> Chapter</label>
            <select class="selection-select" id="chapterSelect" name="chapter_id" required tabindex="-1" style="pointer-events:none; opacity:0.7; background-color:#f8f9fa;">
               <option value="<?=$chapter['chapter_id']?>"><?=$chapter['title']?></option>
            </select>
          </div>

          <div class="selection-group">
            <label class="selection-label"><i class="fa fa-file-text"></i> Topic</label>
            <select class="selection-select" id="topicSelect" name="topic_id" required>
               <option value="<?=$topic['topic_id']?>"><?=$topic['title']?></option>
               <?php foreach ($course['topics'] as $courseTopic) { ?>
                   <?php if ($courseTopic['topic_id'] != $topic['topic_id'] && $courseTopic['chapter_id'] == $chapter['chapter_id']) { ?>
                       <option value="<?=$courseTopic['topic_id']?>"><?=$courseTopic['title']?></option>
                   <?php } ?>
               <?php } ?>
            </select>
          </div>
        </div>

        <!-- Editor Section -->
        <div class="editor-section">
          <label for="contentEditor" class="editor-label">
            <i class="fa fa-edit"></i> Course Content
          </label>
          <textarea class="form-control text" name="text" id="contentEditor"><?=htmlspecialchars($course['content']['data'], ENT_QUOTES, 'UTF-8')?></textarea>
        </div>

        <!-- Action Row -->
        <div class="editor-actions">
          <a href="Courses-content-add.php" class="btn btn-outline-secondary btn-back-content">
            <i class="fa fa-arrow-left"></i> Back
          </a>
          <button type="submit" class="btn btn-primary btn-save-content">
            <i class="fa fa-check"></i> Save Content
          </button>
        </div>
      </form>
    </div>
  </div>

<script type="text/javascript">
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
        $('.text').richText();

        $('.content-form').on('submit', function(e) {
            syncRichTextEditor();
        });

        $('#topicSelect').change(function() {
            $('.alert-success').remove();
            var courseId = $('#courseSelectTopic').val();
            var chapterId = $('#chapterSelect').val();
            var topicId = $(this).val();

            $.post('Action/load-content.php', { course_id: courseId, chapter_id: chapterId, topic_id: topicId }, function(data) {
                var editor = $('.text');
                var richEditor = editor.closest('.richText').find('.richText-editor');
                if (data && data !== '0') {
                    editor.val(data);
                    editor.siblings('.richText-initial').val(data);
                    richEditor.html(data);
                } else {
                    editor.val('');
                    editor.siblings('.richText-initial').val('');
                    richEditor.html('');
                }
            });
        });
    });
</script>
 <!-- Footer -->
<?php include "inc/Footer.php"; ?>

 

<?php
 }else { 
$em = "First login ";
Util::redirect("../login.php", "error", $em);
} ?>
