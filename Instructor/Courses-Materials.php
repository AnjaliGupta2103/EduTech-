<?php 
session_start();
include "../Utils/Util.php";
if (isset($_SESSION['username']) &&
    isset($_SESSION['instructor_id'])) {
  
    include "../Controller/Instructor/CoursesMaterial.php";
  
    $instructor_id = $_SESSION['instructor_id'];
    $row_count = getCountByInstructorId($instructor_id);

    $page = 1;
    $row_num = 5;
    $offset = 0;
   
    $last_page = ceil($row_count / $row_num);
    if(isset($_GET['page'])){
    if($_GET['page'] > $last_page){
        $page = $last_page;
    }else if($_GET['page'] <= 0){
        $page = 1; 
    }else $page = $_GET['page'];
    }
    if($page != 1) $offset = ($page-1) * $row_num;
    $CoursesMaterials = getSomeCoursesMaterialsByInstructorId($offset, $row_num, $instructor_id);
    # Header
    $title = "EduWave - Courses Materials ";
    include "inc/Header.php";

?>

<div class="container">
  <!-- NavBar -->
  <?php include "inc/NavBar.php"; ?>

  <div class="instructor-materials-page">
    <section class="materials-header-card">
      <div>
        <p class="eyebrow">Resources</p>
        <h3 class="mb-1">Course Materials <span>(<?=$row_count?>)</span></h3>
      </div>
      <a href="Courses-Materials-add.php" class="btn btn-primary">
        <i class="fa fa-upload me-1"></i> Upload Material
      </a>
    </section>

    <?php if ($CoursesMaterials) { ?>
      <div class="materials-grid">
        <?php foreach ($CoursesMaterials as $CoursesMaterial) {
          $materialUrl = $CoursesMaterial["URL"];
          $materialName = basename($materialUrl);
          $materialType = strtolower($CoursesMaterial["type"]);
          $fileExtension = strtoupper(pathinfo($materialName, PATHINFO_EXTENSION));
          $isImage = in_array($materialType, ["picture", "image", "jpg", "jpeg", "png", "gif", "webp"])
                    || in_array($fileExtension, ["JPG", "JPEG", "PNG", "GIF", "WEBP", "BMP"]);
          $isPdf = in_array($fileExtension, ["PDF"]);
          $isText = in_array($fileExtension, ["TXT", "MD", "CSV", "JSON"]);
          $isDoc = in_array($fileExtension, ["DOC", "DOCX", "PPT", "PPTX", "XLS", "XLSX"]);
          $isArchive = in_array($fileExtension, ["ZIP", "RAR", "7Z"]);
        ?>
        <div class="material-card shadow-sm">
          <div class="material-preview <?= $isImage ? '' : 'material-preview-file' ?>">
            <?php if ($isImage) { ?>
              <img src="<?=$materialUrl?>" alt="<?=$materialName?>">
            <?php } else { ?>
              <div class="material-preview-icon <?= $isPdf ? 'pdf' : ($isText ? 'text' : ($isDoc ? 'doc' : ($isArchive ? 'archive' : 'default'))) ?>">
                <i class="fa <?= $isPdf ? 'fa-file-pdf-o' : ($isText ? 'fa-file-text-o' : ($isDoc ? 'fa-file-word-o' : ($isArchive ? 'fa-file-archive-o' : 'fa-file-o'))) ?>"></i>
              </div>
              <span class="material-preview-ext"><?=$fileExtension ?: 'FILE'?></span>
            <?php } ?>
          </div>
          <div class="material-card-body">
            <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
              <h6 class="mb-0"><?=$materialName?></h6>
              <span class="material-type-badge"><?=$CoursesMaterial["type"]?></span>
            </div>
            <div class="material-url-wrap">
              <span class="material-url-label">Link</span>
              <a href="<?=$materialUrl?>" class="material-url" target="_blank"><?=htmlspecialchars($materialUrl)?></a>
            </div>
            <a href="<?=$materialUrl?>" class="btn btn-outline-primary btn-sm mt-3" target="_blank">
              Open File
            </a>
          </div>
        </div>
        <?php } ?>
      </div>

      <?php if ($last_page > 1 ) { ?>
      <div class="d-flex justify-content-center mt-4">
          <?php
                $prev = 1;
                $next = 1;
                $next_btn = true;
                $prev_btn = true;
                if($page <= 1) $prev_btn = false; 
                if($last_page ==  $page) $next_btn = false; 
                if($page > 1) $prev = $page - 1;
                if($page < $last_page) $next = $page + 1;
                
                if ($prev_btn){
                ?>
                <a href="Courses-Materials.php?page=<?=$prev?>" class="btn btn-outline-secondary m-1">Prev</a>
               <?php }else { ?>
                <a href="#" class="btn btn-outline-secondary m-1 disabled">Prev</a>
                
               <?php 
               }
               $push_mid = $page;
               if ($page >= 2)  $push_mid = $page - 1;
               if ($page > 3)  $push_mid = $page - 3;
              
               for($i = $push_mid; $i < 5 + $page; $i++){
                if($i == $page){ ?>
                 <a href="Courses-Materials.php?page=<?=$i?>" class="btn btn-primary m-1"><?=$i?></a>
               <?php }else{ ?>
                 <a href="Courses-Materials.php?page=<?=$i?>" class="btn btn-outline-secondary m-1"><?=$i?></a>

               <?php } 
               if($last_page <= $i)break;

                } 
                if($next_btn){
                ?>
                <a href="Courses-Materials.php?page=<?=$next?>" class="btn btn-outline-secondary m-1">Next</a>
            <?php }else { ?>
               <a href="#" class="btn btn-outline-secondary m-1 disabled">Next</a>
            <?php } ?>
      </div>
      <?php } ?>
    <?php } else { ?>
      <div class="alert alert-info rounded-4" role="alert">
        No course materials have been uploaded yet.
      </div>
    <?php } ?>
  </div>
</div>
 <!-- Footer -->
<?php include "inc/Footer.php"; ?>
<script src="../assets/js/jquery-3.5.1.min.js"></script>
<?php
 }else { 
$em = "First login ";
Util::redirect("../login.php", "error", $em);
} ?>
