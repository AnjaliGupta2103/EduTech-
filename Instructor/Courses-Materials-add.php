<?php 
session_start();
include "../Utils/Util.php";
include "../Utils/Validation.php";
if (isset($_SESSION['username']) &&
    isset($_SESSION['instructor_id'])) {


  $title = "EduPulse - Upload Courses Materials ";
  include "inc/Header.php";
?>
<div class="container">
  <!-- NavBar -->
  <?php include "inc/NavBar.php"; ?>

  <div class="instructor-materials-page">
    <section class="materials-header-card mb-4">
      <div>
        <p class="eyebrow">Resources</p>
        <h3 class="mb-1">Upload Material</h3>
      </div>
      <a href="Courses-Materials.php" class="btn btn-outline-primary">All Materials</a>
    </section>

    <div class="upload-material-card shadow-sm">
      <form id="Chapter"
            action="Action/upload-materials.php"
            enctype="multipart/form-data"
            method="POST">
            <?php 
            if (isset($_GET['error'])) { ?>
              <p class="alert alert-warning rounded-3"><?=Validation::clean($_GET['error'])?></p>
            <?php } ?>
            <?php 
            if (isset($_GET['success'])) { ?>
              <p class="alert alert-success rounded-3"><?=Validation::clean($_GET['success'])?></p>
            <?php } ?>
          <div class="mb-3">
              <label for="fileInput" class="form-label fw-semibold">Choose a file</label>
              <input type="file"
                     class="form-control form-control-lg"
                     id="fileInput"
                     name="file"
                     required>
          </div>
          <p class="text-muted small mb-3">Supported types: image, video, PDF, document, ZIP</p>
          <button type="submit" class="btn btn-primary px-4">Upload</button>
      </form>
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