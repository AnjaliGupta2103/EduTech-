<?php
session_start();
include "../Utils/Util.php";
include "../Utils/Validation.php";

if (isset($_SESSION['username']) && isset($_SESSION['instructor_id'])) {
    include "../Controller/Instructor/Course.php";

    $instructor_id = $_SESSION['instructor_id'];
    $course_id = isset($_GET['course_id']) ? (int)$_GET['course_id'] : 0;
    $course = $course_id ? getCourseDetailById($course_id) : 0;

    if (!$course || (int)$course['instructor_id'] !== (int)$instructor_id) {
        Util::redirect("Courses.php", "error", "Course not found");
    }

    $title_value = isset($_GET['title']) ? Validation::clean($_GET['title']) : $course['title'];
    $description_value = isset($_GET['description']) ? Validation::clean($_GET['description']) : $course['description'];

    $course_structure = getById($course_id, 0, 0);
    $course_chapters = is_array($course_structure['chapters']) ? $course_structure['chapters'] : [];
    $course_topics = is_array($course_structure['topics']) ? $course_structure['topics'] : [];

    $title = "EduWave - Edit Course";
    include "inc/Header.php";
?>
<div class="container">
  <?php include "inc/NavBar.php"; ?>

  <div class="course-create-page">
    <section class="course-create-header">
      <div>
        <p class="eyebrow">Instructor Panel</p>
        <h3 class="mb-1">Update Course</h3>
      </div>
    </section>

    <?php if (isset($_GET['error'])) { ?>
      <div class="alert alert-warning rounded-3 mb-4" role="alert">
        <i class="fa fa-exclamation-circle"></i> <?=Validation::clean($_GET['error'])?>
      </div>
    <?php } ?>
    <?php if (isset($_GET['success'])) { ?>
      <div class="alert alert-success rounded-3 mb-4" role="alert">
        <i class="fa fa-check-circle"></i> <?=Validation::clean($_GET['success'])?>
      </div>
    <?php } ?>

    <div class="course-create-grid">
      <form class="course-create-card shadow-sm"
            action="Action/course-update.php"
            method="POST"
            enctype="multipart/form-data">
        <input type="hidden" name="course_id" value="<?=$course_id?>" />

        <div class="course-create-card-top">
          <span class="course-create-pill">Step 1</span>
          <h4 class="mb-0">Edit Course Details</h4>
        </div>

        <div class="mb-3">
          <label for="courseTitle" class="form-label fw-semibold">Course Title</label>
          <input type="text"
                 class="form-control form-control-lg"
                 id="courseTitle"
                 name="title"
                 placeholder="Enter course title"
                 value="<?=$title_value?>"
                 required />
        </div>

        <div class="mb-3">
          <label for="courseDescription" class="form-label fw-semibold">Course Description</label>
          <textarea class="form-control"
                    id="courseDescription"
                    rows="5"
                    name="description"
                    placeholder="Enter course description"
                    required><?=$description_value?></textarea>
        </div>

        <div class="mb-3">
          <label for="Cover" class="form-label fw-semibold">Cover Image</label>
          <div class="mb-2">
            <?php if (!empty($course['cover'])) { ?>
              <img src="../Upload/thumbnail/<?=htmlspecialchars($course['cover'])?>"
                   alt="Course cover"
                   style="max-height: 140px; object-fit: cover; border-radius: 8px;" />
            <?php } else { ?>
              <div class="text-muted">No cover uploaded yet.</div>
            <?php } ?>
          </div>
          <input type="file"
                 class="form-control"
                 id="Cover"
                 name="cover" />
        </div>

        <button type="submit" class="btn btn-primary px-4">Update Course</button>
      </form>

      <div class="course-create-card shadow-sm">
        <div class="course-create-card-top">
          <span class="course-create-pill">Step 2</span>
          <h4 class="mb-0">Existing Chapters & Topics</h4>
        </div>

        <p class="text-muted mb-3">Edit the current course structure and open any topic for content updates.</p>

        <?php if ($course_chapters) { ?>
          <div class="list-group">
            <?php foreach ($course_chapters as $chapter) {
              $chapter_topics = array_filter($course_topics, function($topic) use ($chapter) {
                return (int)$topic['chapter_id'] === (int)$chapter['chapter_id'];
              });
            ?>
              <div class="list-group-item">
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <strong><?=htmlspecialchars($chapter['title'])?></strong>
                  <?php $first_topic_for_chapter = null; foreach ($chapter_topics as $chapter_topic) { $first_topic_for_chapter = $chapter_topic; break; } ?>
                  <a href="Courses-content-edit-page.php?course_id=<?=$course_id?>&chapter_id=<?=$chapter['chapter_id']?><?=!empty($first_topic_for_chapter) ? '&topic_id=' . $first_topic_for_chapter['topic_id'] : ''?>" class="btn btn-sm btn-outline-secondary">
                    <i class="fa fa-edit"></i> Edit Content
                  </a>
                </div>

                <?php if ($chapter_topics) { ?>
                  <ul class="mb-0 ps-3">
                    <?php foreach ($chapter_topics as $topic) { ?>
                      <li class="mb-1">
                        <span><?=htmlspecialchars($topic['title'])?></span>
                        <a href="Courses-content-edit-page.php?course_id=<?=$course_id?>&chapter_id=<?=$chapter['chapter_id']?>&topic_id=<?=$topic['topic_id']?>" class="ms-2 text-primary">
                          <i class="fa fa-arrow-right"></i>
                        </a>
                      </li>
                    <?php } ?>
                  </ul>
                <?php } else { ?>
                  <div class="text-muted small">No topics added yet.</div>
                <?php } ?>
              </div>
            <?php } ?>
          </div>
        <?php } else { ?>
          <div class="alert alert-info mb-0">
            <i class="fa fa-info-circle"></i> No chapters have been created for this course yet.
          </div>
        <?php } ?>
      </div>
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
