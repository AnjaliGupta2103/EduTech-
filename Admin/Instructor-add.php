<?php 
session_start();
include "../Utils/Util.php";
include "../Utils/Validation.php";
if (isset($_SESSION['username']) &&
    isset($_SESSION['admin_id'])) {

  $title = "EduWave - Add Instructor ";
  include "inc/Header.php";

    $fname = $uname = $email =$bd = $lname ="";
    if (isset($_GET["fname"])) {
        $fname = Validation::clean($_GET["fname"]);
    }
    if (isset($_GET["uname"])) {
        $uname = Validation::clean($_GET["uname"]);
    }
    if (isset($_GET["email"])) {
        $email = Validation::clean($_GET["email"]);
    }
    if (isset($_GET["bd"])) {
        $bd = Validation::clean($_GET["bd"]);
    }
    if (isset($_GET["lname"])) {
        $lname = Validation::clean($_GET["lname"]);
    }
?>

<style>
  .instructor-card-wrapper {
    display: flex;
    justify-content: center;
    margin-top: 3rem;
    margin-bottom: 3rem;
  }
  .instructor-card {
    width: min(100%, 600px);
    background: #ffffff;
    border-radius: 24px;
    border: 1px solid rgba(18, 52, 79, 0.08);
    box-shadow: 0 24px 80px rgba(15, 23, 42, 0.08);
    overflow: hidden;
  }
  .instructor-card-header {
    padding: 2rem 2.2rem;
    background: linear-gradient(135deg, #4169e1 0%, #2e5aa8 100%);
    color: #ffffff;
  }
  .instructor-card-header h4 {
    margin: 0;
    font-size: 1.75rem;
    font-weight: 700;
  }
  .instructor-card-header p {
    margin: 0.65rem 0 0;
    opacity: 0.92;
    font-size: 0.95rem;
  }
  .instructor-card-body {
    padding: 2rem 2.2rem 2.5rem;
  }
  .form-label {
    font-weight: 600;
    color: #1f3a5d;
  }
  .form-control {
    border-radius: 0.85rem;
    border: 1px solid #cbd5e1;
    padding: 0.95rem 1rem;
  }
  .form-control:focus {
    border-color: #4169e1;
    box-shadow: 0 0 0 0.2rem rgba(65, 105, 225, 0.16);
  }
  .input-group .btn-outline-secondary {
    border-radius: 0 0.85rem 0.85rem 0;
    color: #1f3a5d;
    border-color: #cbd5e1;
  }
  .input-group .btn-outline-secondary:hover {
    background: #f1f5f9;
  }
  .btn-primary {
    background: #4169e1;
    border-color: #4169e1;
    border-radius: 0.85rem;
    padding: 0.85rem 1.3rem;
    font-weight: 600;
  }
  .btn-primary:hover {
    background: #3354c4;
    border-color: #3354c4;
  }
  .form-text {
    color: #64748b;
    font-size: 0.93rem;
  }
  .alert {
    border-radius: 1rem;
  }
</style>

<div class="container">
  <!-- NavBar -->
  <?php include "inc/NavBar.php"; ?>
  
  <div class="instructor-card-wrapper">
    <div class="instructor-card">
      <div class="instructor-card-header">
        <h4>Add Instructor Profile</h4>
        <p>Fill in the details below to create a new instructor profile.</p>
      </div>
      <div class="instructor-card-body">
        <form action="Action/instructor-add.php" method="POST">
          <?php 
                if (isset($_GET['error'])) { ?>
                    <p class="alert alert-danger"><?=Validation::clean($_GET['error'])?></p>
            <?php } ?>
            <?php 
                if (isset($_GET['success'])) { ?>
                    <p class="alert alert-success"><?=Validation::clean($_GET['success'])?></p>
            <?php } ?>


        <div class="mb-3">
            <label for="instructorFirstName" class="form-label">First Name</label>
            <input type="text" class="form-control" id="instructorFirstName" placeholder="Enter instructor's first name" name="fname" value="<?=$fname?>" required>
        </div>
        <div class="mb-3">
            <label for="instructorLastName" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="instructorLastName" placeholder="Enter instructor's last name" name="lname" value="<?=$lname?>" required>
        </div>
        <div class="mb-3">
            <label for="instructorDOB" class="form-label">Date of Birth</label>
            <input type="date" class="form-control" id="instructorDOB" value="<?=$bd?>" name="date_of_birth" required>
        </div>
        <div class="mb-3">
            <label for="instructorEmail" class="form-label">Email</label>
            <input type="email" class="form-control" id="instructorEmail" placeholder="Enter instructor's email"  name="email" value="<?=$email?>" required>
        </div>
        <div class="mb-3">
            <label for="instructorUsername" class="form-label">Username</label>
            <div class="input-group">
                <input type="text" class="form-control" id="instructorUsername" placeholder="Enter instructor's username" name="username" value="<?=$uname?>" required>
                <button class="btn btn-outline-secondary" type="button" id="generateUsernameButton" onclick="generateUsername()">Auto Generate</button>
            </div>
            <div class="form-text">Leave blank or use auto-generate to create a unique username.</div>
        </div>
        <div class="mb-3">
            <label for="instructorPassword" class="form-label">Password</label>
            <div class="input-group">
                <input type="password" class="form-control" id="instructorPassword" name="password" placeholder="Enter new password" aria-describedby="generatePasswordButton" required>
                <button class="btn btn-outline-secondary" type="button" id="generatePasswordButton" onclick="generatePassword()">Auto Generate</button>
            </div>
            <div class="form-text">Generate a strong temporary password and share it with the instructor.</div>
        </div>
        <div class="d-grid mt-4">
          <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
    </form>
      </div>
    </div>
  </div>

  <script>
    function generatePassword() {
        const randomString = Math.random().toString(36).slice(-6);
        document.getElementById('instructorPassword').value = randomString;
        document.getElementById('instructorPassword').type = "text";
    }

    function generateUsername() {
        const randomString = Math.random().toString(36).slice(-3);
        
        let name = document.getElementById('instructorFirstName').value;
        name = name + randomString;
        document.getElementById('instructorUsername').value = name;
    
    }
</script>
</div>
 <!-- Footer -->
<?php include "inc/Footer.php"; ?>

<?php

}else { 
$em = "First login ";
Util::redirect("../login.php", "error", $em);
} ?>
