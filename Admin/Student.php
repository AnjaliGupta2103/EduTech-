<?php 
session_start();
include "../Utils/Util.php";
include "../Utils/Validation.php";
if (isset($_SESSION['username']) &&
    isset($_SESSION['admin_id'])) {
    
  if (isset($_GET['student_id'])) {
    include "../Controller/Admin/Student.php";
    $_id = Validation::clean($_GET['student_id']);
    $student = getById($_id);
   if (empty($student['student_id'])) {
     $em = "Invalid Student id";
     Util::redirect("index.php", "error", $em);
   }
   // get Certificates
   $certificates = getCertificate($_id);
    # Header 
    $title = "EduWave - Student ";
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
                  <img class="circle profile-img mb-3" src="../Upload/profile/<?=$student['profile_img']?>" alt="PROFILE IMG" width="150" height="150">
                  <h5 class="mb-3"><?=$student['first_name']?> <?=$student['last_name']?></h5>
                  <a href="Reset-Password.php?for=Student&student_id=<?=$student['student_id']?>" class="btn btn-primary btn-sm">Reset Password</a>
                </div>
              </div>
              <div class="col-lg-8">
                <div class="card shadow-sm student-detail-card mb-4">
                  <div class="card-body">
                    <h4 class="card-title mb-3">Student Information</h4>
                    <ul class="list-group list-group-flush">
                      <li class="list-group-item"><strong>First name:</strong> <?=$student['first_name']?></li>
                      <li class="list-group-item"><strong>Last name:</strong> <?=$student['last_name']?></li>
                      <li class="list-group-item"><strong>Email:</strong> <?=$student['email']?></li>
                      <li class="list-group-item"><strong>Date of birth:</strong> <?=$student['date_of_birth']?></li>
                      <li class="list-group-item"><strong>Joined at:</strong> <?=$student['date_of_joined']?></li>
                      <li class="list-group-item"><strong>Student id:</strong> <?=$student['student_id']?></li>
                      <li class="list-group-item"><strong>Username:</strong> <?=$student['username']?></li>
                    </ul>
                  </div>
                </div>
                <?php if (!empty($certificates[0]["certificate_id"])) { ?>
                <div class="card shadow-sm student-detail-card">
                  <div class="card-body">
                    <h4 class="card-title mb-3">Certificates</h4>
                    <ul class="list-group list-group-flush certificate-list">
                      <?php 
                        $i = 0;
                        foreach ($certificates as $certificate) { $i++;
                      ?>
                      <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span><strong><?=$i ?>.</strong> <a class="text-decoration-none" href="../Certificate.php?certificate_id=<?=$certificate['certificate_id']?>"><?=$certificate['course_title']?></a></span>
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
  $em = "Invalid Student id";
  Util::redirect("index.php", "error", $em);
  }

}else { 
$em = "First login ";
Util::redirect("../login.php", "error", $em);
} ?>
