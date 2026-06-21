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
        <p class="eyebrow">Student Settings</p>
        <h3>Edit Profile</h3>
      </div>
    </div>

    <?php if (isset($_GET['error'])) { ?>
      <p class="alert alert-danger"><?= Validation::clean($_GET['error']) ?></p>
    <?php } ?>
    <?php if (isset($_GET['success'])) { ?>
      <p class="alert alert-success"><?= Validation::clean($_GET['success']) ?></p>
    <?php } ?>

    <div class="row g-4">
      <div class="col-lg-7">
        <div class="info-card profile-form">
          <h5 class="mb-3">Personal Details</h5>
          <form action="Action/upload-profile-details.php" method="POST">
            <div class="mb-3">
              <label class="form-label">First name</label>
              <input type="text" class="form-control" name="first_name" value="<?= htmlspecialchars($student['first_name']) ?>">
            </div>
            <div class="mb-3">
              <label class="form-label">Last name</label>
              <input type="text" class="form-control" name="last_name" value="<?= htmlspecialchars($student['last_name']) ?>">
            </div>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($student['email']) ?>">
            </div>
            <div class="mb-3">
              <label class="form-label">Date of birth</label>
              <input type="date" class="form-control" name="date_of_birth" value="<?= htmlspecialchars($student['date_of_birth']) ?>">
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
          </form>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="info-card profile-form" id="ChangePassword">
          <h5 class="mb-3">Change Password</h5>
          <form method="post" action="Action/change-password.php">
            <div class="mb-3">
              <label class="form-label">Current password</label>
              <input type="password" class="form-control" name="password" placeholder="Current password">
            </div>
            <div class="mb-3">
              <label class="form-label">New Password</label>
              <div class="input-group">
                <input type="password" class="form-control" id="instructorPassword" name="new_password" placeholder="Enter new password">
                <button class="btn btn-outline-secondary" type="button" id="generatePasswordButton" onclick="generatePassword()">Auto Generate</button>
              </div>
            </div>
            <div class="mb-3">
              <label class="form-label">Confirm password</label>
              <input type="password" class="form-control" id="confirmPassword" name="confirm_password" placeholder="Confirm password">
            </div>
            <button type="submit" class="btn btn-primary">Change Password</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  function generatePassword() {
      const randomString = Math.random().toString(36).slice(-8);
      document.getElementById('instructorPassword').value = randomString;
      document.getElementById('confirmPassword').value = randomString;
      document.getElementById('instructorPassword').type = 'text';
      document.getElementById('confirmPassword').type = 'text';
  }
</script>

 <!-- Footer -->
<?php include "inc/Footer.php"; ?>
<?php
 }else { 
$em = "First login ";
Util::redirect("../login.php", "error", $em);
} ?>
