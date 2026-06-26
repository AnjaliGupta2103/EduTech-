<?php 
session_start();
include "../Utils/Util.php";
include "../Utils/Validation.php";
if (isset($_SESSION['username']) &&
    isset($_SESSION['student_id'])) {
    include "../Controller/Student/Student.php";

    $_id =  $_SESSION['student_id'];
    $student = getById($_id);

   if (empty($student['student_id'])) {
     $em = "Invalid Student id";
     Util::redirect("../logout.php", "error", $em);
   }
   // get Certificates
   $certificates = getCertificate($_id);
   $enrolledCoursesCount = getEnrolledCount($_id);
   $certificateCount = count($certificates);
   $profileCompletion = 0;
   if (!empty($student['profile_img'])) $profileCompletion += 20;
   foreach (['first_name', 'last_name', 'email', 'date_of_birth'] as $field) {
     if (!empty($student[$field])) $profileCompletion += 15;
   }
   if (!empty($student['username'])) $profileCompletion += 10;
   if (!empty($certificates)) $profileCompletion += 20;
   $profileCompletion = min($profileCompletion, 100);
    # Header
    $title = "EduPulse - Students ";
    include "inc/Header.php";

?>
<div class="container">
  <!-- NavBar & Profile-->
  <?php include "inc/NavBar.php"; 
        include "inc/Profile.php"; ?>

    <div class="profile-header-row">
      <div>
        <p class="eyebrow">Student Profile</p>
        <h3>My Profile</h3>
        <p class="profile-subtitle">Track your learning journey, progress, and account details in one place.</p>
      </div>
      <div class="profile-header-actions">
        <a href="Profile-Edit.php" class="btn btn-primary">Edit Profile</a>
        <a href="Profile-Edit.php#ChangePassword" class="btn btn-outline-secondary">Change Password</a>
      </div>
    </div>

    <div class="profile-overview-card student-hero-card">
      <div class="profile-overview-left">
        <div class="student-avatar-shell">
          <img src="../Upload/profile/<?= htmlspecialchars($student['profile_img']) ?>" class="overview-avatar" alt="Profile image">
        </div>
        <div class="profile-identity-details">
          <div class="profile-pill-row">
            <span class="badge bg-success">Active Student</span>
            <span class="badge bg-light text-dark">Verified</span>
          </div>
          <h4 class="mb-1"><?= htmlspecialchars($student['first_name']) . ' ' . htmlspecialchars($student['last_name']) ?></h4>
          <p class="text-muted mb-0">@<?= htmlspecialchars($student['username']) ?></p>
          <p class="student-hero-meta">Student since <?= htmlspecialchars($student['date_of_joined']) ?></p>
        </div>
      </div>

      <div class="profile-overview-right">
        <div class="profile-overview-summary">
          <div class="profile-summary-item">
            <small>Enrolled</small>
            <strong><?= $enrolledCoursesCount ?> Courses</strong>
          </div>
          <div class="profile-summary-item">
            <small>Certificates</small>
            <strong><?= $certificateCount ?> Earned</strong>
          </div>
          <div class="profile-summary-item wide-item">
            <small>Member Since</small>
            <strong><?= htmlspecialchars($student['date_of_joined']) ?></strong>
          </div>
        </div>
        <div class="profile-completion">
          <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="small text-muted">Profile completion</span>
            <strong><?= $profileCompletion ?>%</strong>
          </div>
          <div class="progress" style="height: 10px;">
            <div class="progress-bar bg-primary" role="progressbar" style="width: <?= $profileCompletion ?>%"></div>
          </div>
        </div>
      </div>
    </div>

    <div class="profile-stats-grid">
      <div class="profile-stat-card primary">
        <span class="profile-stat-icon"><i class="fa fa-book"></i></span>
        <div>
          <small>Enrolled</small>
          <strong><?= $enrolledCoursesCount ?> Courses</strong>
        </div>
      </div>
      <div class="profile-stat-card success">
        <span class="profile-stat-icon"><i class="fa fa-certificate"></i></span>
        <div>
          <small>Certificates</small>
          <strong><?= $certificateCount ?> Earned</strong>
        </div>
      </div>
      <div class="profile-stat-card warning">
        <span class="profile-stat-icon"><i class="fa fa-calendar"></i></span>
        <div>
          <small>Member Since</small>
          <strong><?= htmlspecialchars($student['date_of_joined']) ?></strong>
        </div>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-lg-8">
        <div class="info-card">
          <div class="section-heading-row">
            <h5 class="mb-0">Personal Details</h5>
            <a href="Profile-Edit.php" class="text-primary small">Update</a>
          </div>
          <div class="info-grid">
            <div class="info-item"><strong>First Name</strong><span><?= htmlspecialchars($student['first_name']) ?></span></div>
            <div class="info-item"><strong>Last Name</strong><span><?= htmlspecialchars($student['last_name']) ?></span></div>
            <div class="info-item"><strong>Email</strong><span><?= htmlspecialchars($student['email']) ?></span></div>
            <div class="info-item"><strong>Date of Birth</strong><span><?= htmlspecialchars($student['date_of_birth']) ?></span></div>
            <div class="info-item"><strong>Joined At</strong><span><?= htmlspecialchars($student['date_of_joined']) ?></span></div>
            <div class="info-item"><strong>Student ID</strong><span><?= htmlspecialchars($student['student_id']) ?></span></div>
          </div>
        </div>
      </div>
      <div class="col-lg-4">
        <div class="info-card certificate-panel">
          <div class="section-heading-row">
            <h5 class="mb-0">Certificates</h5>
            <span class="badge bg-light text-dark"><?= $certificateCount ?></span>
          </div>
          <?php if (!empty($certificates)) { ?>
            <ul class="certificate-list">
              <?php $i = 0; foreach ($certificates as $certificate) { $i++; ?>
                <li>
                  <span><?= htmlspecialchars($certificate['course_title']) ?></span>
                  <a href="../Certificate.php?certificate_id=<?= (int)$certificate['certificate_id'] ?>" class="btn btn-sm btn-outline-primary">Open</a>
                </li>
              <?php } ?>
            </ul>
          <?php } else { ?>
            <div class="empty-state">
              <i class="fa fa-certificate"></i>
              <p class="mb-0">No certificates yet.</p>
            </div>
          <?php } ?>
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
