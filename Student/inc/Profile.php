<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<div class="profile-page student-profile-page d-flex gap-4 flex-wrap align-items-start">
  <div class="profile-sidebar-modern student-sidebar-modern shadow-sm">
    <div class="profile-sidebar-top">
      <div class="profile-avatar-wrap">
        <img src="../Upload/profile/<?= htmlspecialchars($student['profile_img']) ?>" class="profile-avatar" alt="Profile image">
      </div>
      <h4 class="mt-3 mb-1"><?= htmlspecialchars($student['username']) ?></h4>
      <p class="profile-user-email mb-2"><?= htmlspecialchars($student['email']) ?></p>
      <span class="profile-role">Student</span>
    </div>
    <div class="profile-sidebar-links">
      <a href="Profile-View.php" class="<?= $currentPage === 'Profile-View.php' ? 'active' : '' ?>">
        <i class="fa fa-user"></i> <span>View Profile</span>
      </a>
      <a href="Profile-Edit.php" class="<?= $currentPage === 'Profile-Edit.php' ? 'active' : '' ?>">
        <i class="fa fa-edit"></i> <span>Edit Profile</span>
      </a>
      <a href="Profile-Edit.php#ChangePassword">
        <i class="fa fa-lock"></i> <span>Change Password</span>
      </a>
      <a href="../Logout.php">
        <i class="fa fa-sign-out"></i> <span>Logout</span>
      </a>
    </div>
    <form action="Action/upload-profile.php" method="POST" enctype="multipart/form-data" class="profile-upload-form">
      <input type="file" class="form-control form-control-sm" name="profile_picture">
      <button type="submit" class="btn btn-light btn-sm w-100 mt-2">Upload Photo</button>
    </form>
  </div>

  <div class="profile-content-panel student-profile-content-panel">