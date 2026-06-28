
<?php 
session_start();
include "../Utils/Util.php";
include "../Utils/Validation.php";
if (isset($_SESSION['username']) &&
    isset($_SESSION['instructor_id'])) {
    include "../Controller/Instructor/Course.php";
    $instructor_id = $_SESSION['instructor_id'];
    $courses = getCoursesByInstructorId($instructor_id);

    # Header
    $title = "EduWave - Create Course ";
    include "inc/Header.php";
    
    $title = $description  ="";
    if (isset($_GET["title"])) {
        $title = Validation::clean($_GET["title"]);
    }
    if (isset($_GET["description"])) {
        $description = Validation::clean($_GET["description"]);
    }
?>

<div class="container">
  <!-- NavBar -->
  <?php include "inc/NavBar.php"; ?>

  <div class="content-create-page">
    <section class="content-hero-card">
      <div class="hero-copy">
        <span class="hero-pill">Instructor Workspace</span>
        <h3>Create or update your lesson content</h3>
        <p>Select the course, chapter, and topic you want to work on.</p>
      </div>
      <div class="hero-badge">3 quick steps</div>
    </section>

    <div class="content-selection-card">
      <!-- Step Indicator -->
      <div class="step-indicator-horizontal">
        <div class="step-item active">
          <div class="step-number">1</div>
          <div class="step-label">Course</div>
        </div>
        <div class="step-connector"></div>
        <div class="step-item">
          <div class="step-number">2</div>
          <div class="step-label">Chapter</div>
        </div>
        <div class="step-connector"></div>
        <div class="step-item">
          <div class="step-number">3</div>
          <div class="step-label">Topic</div>
        </div>
      </div>

      <form action="Action/create-content.php" method="POST">
        <div class="form-grid-2col">
          <div class="form-group-compact">
            <label for="courseSelectTopic" class="form-label-compact">
              <i class="fa fa-book"></i> Course
            </label>
            <select class="form-select-compact" id="courseSelectTopic" name="course_id" required>
               <option value="" selected>Select a course</option>
               <?php if ($courses) { ?>
                    <?php foreach ($courses as $course) { ?>
                        <option value="<?=$course['course_id']?>"><?=$course['title']?></option>
                    <?php }?>
                <?php } ?>
            </select>
          </div>

          <div class="form-group-compact">
            <label for="chapterSelect" class="form-label-compact">
              <i class="fa fa-layer-group"></i> Chapter
            </label>
            <select class="form-select-compact" 
                    id="chapterSelect" 
                    name="chapter_id" 
                    required
                    disabled>
              <option value="">Select a chapter</option>
            </select>
          </div>
        </div>

        <div class="form-group-compact">
          <label for="topicSelect" class="form-label-compact">
            <i class="fa fa-file-text"></i> Topic
          </label>
          <select class="form-select-compact" 
                  id="topicSelect" 
                  name="topic_id" 
                  required
                  disabled>
            <option value="">Select a chapter first</option>
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

<script type="text/javascript">
     $(document).ready(function() {
         var loadedCourseId = null;

    // Function to load chapters
    function loadChapters() {
        var courseSelectTopicVal = $("#courseSelectTopic").val();
        if (!courseSelectTopicVal) {
            $("#chapterSelect").html('<option value="">Select a chapter</option>');
            $("#chapterSelect").prop('disabled', true);
            $("#topicSelect").html('<option value="">Select a chapter first</option>');
            $("#topicSelect").prop('disabled', true);
            loadedCourseId = null;
            return;
        }

        if (loadedCourseId === courseSelectTopicVal) {
            return;
        }

        $("#chapterSelect").prop('disabled', false);
        $("#chapterSelect").html('<option value="">Loading chapters...</option>');
        $("#topicSelect").html('<option value="">Select a chapter first</option>');
        $("#topicSelect").prop('disabled', true);

        $.post("Action/load-chapters.php", 
              {'course_id': courseSelectTopicVal}, 
              function(data, status){
                    if(status == "success"){
                        if (data != 0) {
                            $("#chapterSelect").html('<option value="">Select a chapter</option>' + data);
                        } else {
                            $("#chapterSelect").html('<option value="">No chapters available. Create one first.</option>');
                        }
                        loadedCourseId = courseSelectTopicVal;
                    }
        });
    }
    
    // Function to load topics
    function loadTopics() {
        var chapterSelectTopicVal = $("#chapterSelect").val();
        if (chapterSelectTopicVal) {
            $("#topicSelect").prop('disabled', false);
            $("#topicSelect").html('<option value="">Loading topics...</option>');
            $.post("Action/load-topics.php", 
                  {'chapter_id': chapterSelectTopicVal}, 
                  function(data, status){
                        if(status == "success"){
                            if (data != 0) {
                                $("#topicSelect").html(data);
                            } else {
                                $("#topicSelect").html('<option value="">No topics available. Create one first.</option>');
                            }
                        }
            });
        }
    }
    
    $("#courseSelectTopic").change(function(){
        loadChapters();
    });

    $("#chapterSelect").on('focus', function(){
        if ($("#courseSelectTopic").val()) {
            loadChapters();
        }
    });

    $("#chapterSelect").change(function(){
        loadTopics();
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
