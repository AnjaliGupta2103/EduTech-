<?php 
session_start();
include "../Utils/Util.php";
include "../Utils/Validation.php";
if (isset($_SESSION['username']) &&
    isset($_SESSION['instructor_id'])) {
    include "../Controller/Instructor/Instructor.php";

    $_id =  $_SESSION['instructor_id'];
    $instructor = getById($_id);

   if (empty($instructor['instructor_id'])) {
     $em = "Invalid instructor id";
     Util::redirect("../logout.php", "error", $em);
   }
    # Header
    $title = "EduPulse - Instructor ";
    include "inc/Header.php";

?>
<div class="container">
  <!-- NavBar & Profile-->
  <?php include "inc/NavBar.php"; 
        include "inc/Profile.php"; ?>
      <div class="profile-overview-card">
        <div class="profile-overview-top">
          <div class="profile-overview-left">
            <img src="../Upload/profile/<?=$instructor['profile_img']?>" class="overview-avatar" alt="Profile image">
            <div class="profile-identity-details">
              <div class="profile-pill-row">
                <span class="badge bg-info">Instructor</span>
                <span class="badge bg-light text-dark">Verified</span>
              </div>
              <h4 class="mb-1"><?=htmlspecialchars($instructor['first_name'])?> <?=htmlspecialchars($instructor['last_name'])?></h4>
              <p class="text-muted mb-0">@<?=htmlspecialchars($instructor['username'])?></p>
            </div>
          </div>

          <div class="profile-overview-center">
            <div class="profile-overview-actions">
              <a href="Profile-Edit.php" class="btn btn-primary">Edit Profile</a>
              <a href="Profile-Edit.php#ChangePassword" class="btn btn-outline-secondary">Change Password</a>
            </div>
          </div>
        </div>

        <div class="profile-overview-right">
          <div class="profile-overview-summary">
            <div class="profile-summary-item">
              <small>Member Since</small>
              <strong><?=htmlspecialchars($instructor['date_of_joined'])?></strong>
            </div>
            <div class="profile-summary-item">
              <small>Email</small>
              <strong><?=htmlspecialchars($instructor['email'])?></strong>
            </div>
            <div class="profile-summary-item">
              <small>Date of Birth</small>
              <strong><?=htmlspecialchars($instructor['date_of_birth'])?></strong>
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-4">
        <div class="col-lg-12">
          <div class="info-card">
            <div class="section-heading-row">
              <h5 class="mb-0">Account Information</h5>
            </div>
            <div class="info-grid">
              <div class="info-item">
                <strong>First Name</strong>
                <span><?=htmlspecialchars($instructor['first_name'])?></span>
              </div>
              <div class="info-item">
                <strong>Last Name</strong>
                <span><?=htmlspecialchars($instructor['last_name'])?></span>
              </div>
              <div class="info-item">
                <strong>Email</strong>
                <span><?=htmlspecialchars($instructor['email'])?></span>
              </div>
              <div class="info-item">
                <strong>Username</strong>
                <span><?=htmlspecialchars($instructor['username'])?></span>
              </div>
              <div class="info-item">
                <strong>Date of Birth</strong>
                <span><?=htmlspecialchars($instructor['date_of_birth'])?></span>
              </div>
              <div class="info-item">
                <strong>Joined At</strong>
                <span><?=htmlspecialchars($instructor['date_of_joined'])?></span>
              </div>
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
$em = "First login ";
Util::redirect("../login.php", "error", $em);
} ?>
