<div class="profile-page instructor-profile-page">
  <div class="profile-sidebar-modern instructor-sidebar-modern">
    <div class="profile-sidebar-top">
      <div class="profile-avatar-wrap">
        <img src="../Upload/profile/<?=$instructor['profile_img']?>" class="profile-avatar" alt="PROFILE IMG">
      </div>
      <h5 class="mb-2 mt-3"><?=$instructor['first_name']?> <?=$instructor['last_name']?></h5>
      <p class="profile-user-email mb-0">@<?=$instructor['username']?></p>
    </div>
    <div class="profile-sidebar-links">
      <a href="Profile-View.php"><i class="fa fa-eye"></i> View Profile</a>
      <a href="Profile-Edit.php"><i class="fa fa-edit"></i> Edit Profile</a>
      <a href="Profile-Edit.php#ChangePassword"><i class="fa fa-lock"></i> Change Password</a>
      <a href="../Logout.php"><i class="fa fa-sign-out"></i> Logout</a>
    </div>
    <div class="profile-upload-form px-3 pb-3">
      <form action="Action/upload-profile.php" enctype="multipart/form-data" method="POST">
        <input type="file" class="form-control form-control-sm mb-2" name="profile_picture">
        <button type="submit" class="btn btn-sm btn-light w-100">Update Picture</button>
      </form>
    </div>
  </div>
  <div class="profile-content-panel instructor-profile-content-panel">