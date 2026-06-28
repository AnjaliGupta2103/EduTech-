<?php 
session_start();
include "../Utils/Util.php";
include "../Utils/Validation.php";
if (isset($_SESSION['username']) &&
    isset($_SESSION['admin_id'])) {
    
  if (isset($_GET['instructor_id'])) {
    include "../Controller/Admin/Instructor.php";
    $_id = Validation::clean($_GET['instructor_id']);
    $instructor = getById($_id);
   if (empty($instructor['instructor_id'])) {
     $em = "Invalid Student id";
     Util::redirect("index.php", "error", $em);
   }
   $courses = getCourseById($_id);
    # Header 
    $title = "EduPulse - Instructor";
    include "inc/Header.php";
?>

<div class="container">
  <!-- NavBar -->
  <?php include "inc/NavBar.php"; ?>
  
  <div class="list-table pt-5">
    <div class="row g-4 justify-content-center">
      <div class="col-12">
        <div class="card shadow-sm border-0 admin-list-card">
          <div class="card-body">
            <div class="row g-4">
              <div class="col-lg-4">
                <div class="card student-profile-card shadow-sm text-center p-4">
                  <?php
                    $profileImage = '../Upload/profile/default.jpg';
                    if (!empty($instructor['profile_img'])) {
                      $candidatePath = '../Upload/profile/' . $instructor['profile_img'];
                      if (file_exists($candidatePath)) {
                        $profileImage = $candidatePath;
                      }
                    }
                  ?>
                  <img class="circle profile-img mb-3" src="<?= htmlspecialchars($profileImage) ?>" alt="PROFILE IMG" width="150" height="150" onerror="this.onerror=null;this.src='../Upload/profile/default.jpg';">
                  <h5 class="mb-3"><?= htmlspecialchars($instructor['first_name']) ?> <?= htmlspecialchars($instructor['last_name']) ?></h5>
                  <a href="Reset-Password.php?for=instructor&instructor_id=<?= htmlspecialchars($instructor['instructor_id']) ?>" class="btn btn-primary btn-sm">Reset Password</a>
                </div>
              </div>
              <div class="col-lg-8">
                <div class="card shadow-sm student-detail-card mb-4">
                  <div class="card-body">
                    <h4 class="card-title mb-3">Instructor Information</h4>
                    <ul class="list-group list-group-flush">
                      <li class="list-group-item"><strong>First name:</strong> <?=$instructor['first_name']?></li>
                      <li class="list-group-item"><strong>Last name:</strong> <?=$instructor['last_name']?></li>
                      <li class="list-group-item"><strong>Email:</strong> <?=$instructor['email']?></li>
                      <li class="list-group-item"><strong>Date of birth:</strong> <?=$instructor['date_of_birth']?></li>
                      <li class="list-group-item"><strong>Joined at:</strong> <?=$instructor['date_of_joined']?></li>
                      <li class="list-group-item"><strong>Instructor id:</strong> <?=$instructor['instructor_id']?></li>
                      <li class="list-group-item"><strong>Username:</strong> <?=$instructor['username']?></li>
                    </ul>
                  </div>
                </div>
                <?php if (!empty($courses)) { ?>
                <div class="card shadow-sm student-detail-card">
                  <div class="card-body">
                    <h4 class="card-title mb-3">Courses</h4>
                    <ul class="list-group list-group-flush certificate-list">
                      <?php 
                        $i = 0;
                        foreach ($courses as $course) { $i++;
                      ?>
                      <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><strong><?=$i ?>.</strong> <a class="text-decoration-none" href="Course.php?course_id=<?=$course['course_id']?>"><?=$course['title']?></a></span>
                      </li>
                      <?php } ?>
                    </ul>
                  </div>
                </div>
                <?php } ?>
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
  $em = "Invalid instructor id";
  Util::redirect("index.php", "error", $em);
  }

}else { 
$em = "First login ";
Util::redirect("../login.php", "error", $em);
} ?>