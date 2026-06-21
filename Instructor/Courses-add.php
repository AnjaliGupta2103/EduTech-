
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
    $title = "EduPulse - Create Course ";
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

  <div class="course-create-page">
    <section class="course-create-header">
      <div>
        <p class="eyebrow">Instructor Panel</p>
        <h3 class="mb-1">Create Course Content</h3>
      </div>
    </section>

    <div class="course-create-grid">
      <form id="courseForm"
            class="course-create-card shadow-sm"
            action="Action/course-add.php"
            method="POST"
            enctype="multipart/form-data">
        <?php if (isset($_GET['error'])) { ?>
          <p class="alert alert-warning rounded-3 mb-3"><?=Validation::clean($_GET['error'])?></p>
        <?php } ?>
        <?php if (isset($_GET['success'])) { ?>
          <p class="alert alert-success rounded-3 mb-3"><?=Validation::clean($_GET['success'])?></p>
        <?php } ?>

        <div class="course-create-card-top">
          <span class="course-create-pill">Step 1</span>
          <h4 class="mb-0">Create a New Course</h4>
        </div>

        <div class="mb-3">
          <label for="courseTitle" class="form-label fw-semibold">Course Title</label>
          <input type="text"
                 class="form-control form-control-lg"
                 id="courseTitle"
                 name="title"
                 placeholder="Enter course title"
                 value="<?=$title?>"
                 required />
        </div>

        <div class="mb-3">
          <label for="courseDescription" class="form-label fw-semibold">Course Description</label>
          <textarea class="form-control"
                    id="courseDescription"
                    rows="5"
                    name="description"
                    placeholder="Enter course description"
                    required ><?=$description?></textarea>
        </div>

        <div class="mb-3">
          <label for="Cover" class="form-label fw-semibold">Cover Image</label>
          <input type="file"
                 class="form-control"
                 id="Cover"
                 name="cover" />
        </div>

        <button type="submit" class="btn btn-primary px-4">Create Course</button>
      </form>

      <form id="Chapter"
            class="course-create-card shadow-sm"
            action="Action/course-chapter-add.php"
            method="POST">
        <div class="course-create-card-top">
          <span class="course-create-pill">Step 2</span>
          <h4 class="mb-0">Create a New Chapter</h4>
        </div>

        <div class="mb-3">
          <label for="courseSelect" class="form-label fw-semibold">Select Course</label>
          <select class="form-select" id="courseSelect" name="course_id" required>
            <?php if ($courses) { ?>
              <?php foreach ($courses as $course) { ?>
                <option value="<?=$course['course_id']?>"><?=$course['title']?></option>
              <?php } ?>
            <?php } ?>
          </select>
        </div>

        <div class="mb-3">
          <label for="chapterTitle" class="form-label fw-semibold">Chapter Title</label>
          <input type="text"
                 class="form-control"
                 id="chapterTitle"
                 placeholder="Enter chapter title"
                 name="chapter_title"
                 required>
        </div>

        <button type="submit" class="btn btn-primary px-4">Create Chapter</button>
      </form>

      <form id="Topic"
            class="course-create-card shadow-sm"
            action="Action/course-topic-add.php"
            method="POST">
        <div class="course-create-card-top">
          <span class="course-create-pill">Step 3</span>
          <h4 class="mb-0">Create a New Topic</h4>
        </div>

        <div class="mb-3">
          <label for="courseSelectTopic" class="form-label fw-semibold">Select Course</label>
          <select class="form-select" id="courseSelectTopic" name="course_id" required>
            <?php if ($courses) { ?>
              <?php foreach ($courses as $course) { ?>
                <option value="<?=$course['course_id']?>"><?=$course['title']?></option>
              <?php } ?>
            <?php } ?>
          </select>
        </div>

        <div class="mb-3">
          <label for="chapterSelect" class="form-label fw-semibold">Select Chapter</label>
          <select class="form-select"
                  id="chapterSelect"
                  name="chapter_id"
                  required>
          </select>
        </div>

        <div class="mb-3">
          <label for="topicTitle" class="form-label fw-semibold">Topic Title</label>
          <input type="text"
                 class="form-control"
                 id="topicTitle"
                 placeholder="Enter topic title"
                 name="topic_title"
                 required>
        </div>

        <button type="submit" class="btn btn-primary px-4">Create Topic</button>
      </form>
    </div>
  </div>
</div>

<script src="../assets/js/jquery-3.5.1.min.js"></script>
<script type="text/javascript">
    $("#courseSelectTopic").change(function(){
        var $courseSelectTopicVal = $("#courseSelectTopic").val();
        $.post("Action/load-chapters.php",
              {'course_id': $courseSelectTopicVal},
              function(data, status){
                    if(status == "success"){
                        if (data != 0) {
                            $("#chapterSelect").html(data);
                        } else {
                            alert("First create a chapter");
                            $("#chapterSelect").html("");
                        }
                    }
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
