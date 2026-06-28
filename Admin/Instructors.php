<?php 
session_start();
include "../Utils/Util.php";
if (isset($_SESSION['username']) &&
    isset($_SESSION['admin_id'])) {
    include "../Controller/Admin/Instructor.php";
    $current_status = 'all';
    if (isset($_GET['status'])) {
        $current_status = normalizeInstructorStatus($_GET['status']);
    }

    $allowed_rows = [5, 10, 20, 50];
    $row_num = 5;
    if (isset($_GET['rows_per_page']) && in_array((int) $_GET['rows_per_page'], $allowed_rows, true)) {
        $row_num = (int) $_GET['rows_per_page'];
    }

    $all_count = getCount('all');
    $active_count = getCount('active');
    $inactive_count = getCount('inactive');
    $row_count = getCount($current_status);

    $page = 1;
    $offset = 0;
    $last_page = max(1, ceil($row_count / $row_num));
    if(isset($_GET['page'])){
        $page = (int) $_GET['page'];
        if($_GET['page'] > $last_page){
            $page = $last_page;
        }else if($_GET['page'] <= 0){
            $page = 1; 
        }
    }
    if($page != 1) $offset = ($page-1) * $row_num;
    $serial_start = $offset + 1;
    $start_record = $row_count > 0 ? $offset + 1 : 0;
    $end_record = min($offset + $row_num, $row_count);
    $instructors = getSomeInstructors($offset, $row_num, $current_status);
    # Header
    $title = "EduPulse - Instructors";
    include "inc/Header.php";
    
?>

<div class="container">
  <!-- NavBar -->
  <?php include "inc/NavBar.php"; ?>
  
  <div class="list-table pt-5">
    <?php if (!empty($instructors)) { ?>
    <div class="card shadow-sm border-0 admin-list-card mb-4">
      <div class="card-body">
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between mb-4 gap-3">
          <div>
            <h4 class="mb-1" id="instructor-list-title"><?= $current_status === 'all' ? 'All Instructors' : ($current_status === 'active' ? 'Active Instructors' : 'Inactive Instructors') ?> (<?=$row_count?>)</h4>
            <div class="text-muted small">Showing <?=$start_record?> to <?=$end_record?> of <?=$row_count?> instructors</div>
          </div>
          <form method="get" class="d-flex align-items-center gap-2 mb-0">
            <input type="hidden" name="status" value="<?=$current_status?>">
            <label class="mb-0 small text-muted">Records per page</label>
            <select name="rows_per_page" class="form-select form-select-sm" onchange="this.form.submit()">
              <?php foreach ($allowed_rows as $num) { ?>
                <option value="<?=$num?>" <?= $row_num === $num ? 'selected' : '' ?>><?=$num?></option>
              <?php } ?>
            </select>
            <noscript><button type="submit" class="btn btn-primary btn-sm">Apply</button></noscript>
          </form>
        </div>
        <div class="mb-3">
          <ul class="nav nav-tabs">
            <li class="nav-item">
              <a class="nav-link <?= $current_status == 'all' ? 'active' : '' ?>" href="Instructors.php?status=all&rows_per_page=<?=$row_num?>">All (<?=$all_count?>)</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= $current_status == 'active' ? 'active' : '' ?>" href="Instructors.php?status=active&rows_per_page=<?=$row_num?>">Active (<?=$active_count?>)</a>
            </li>
            <li class="nav-item">
              <a class="nav-link <?= $current_status == 'inactive' ? 'active' : '' ?>" href="Instructors.php?status=inactive&rows_per_page=<?=$row_num?>">Inactive (<?=$inactive_count?>)</a>
            </li>
          </ul>
        </div>
        <div class="table-responsive">
          <table class="table table-hover table-bordered align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>Id</th>
                <th>Full name</th>
                <th>Status</th>
                <th>Block / Unblock</th>
                <?php if ($current_status === 'inactive') { ?>
                  <th>Delete</th>
                <?php } ?>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($instructors as $instructor) {
                  $serial = $serial_start++;
                  $instructor_id = $instructor["instructor_id"];
                  $text_temp = $instructor["status"] == "Active" ? "Block": "Unblock";
            ?>
              <tr>
                <td><?=$serial?></td>
                <td><a class="text-decoration-none" href="instructor.php?instructor_id=<?=$instructor_id?>"><?=$instructor["first_name"]?> <?=$instructor["last_name"]?></a></td>
                <td class="status"><span class="badge bg-<?= $instructor["status"] == "Active" ? 'success' : 'secondary' ?>"><?=$instructor["status"]?></span></td>
                <td class="action_btn">
                  <a href="javascript:void(0)" onclick="ChangeStatus(this, <?=$instructor_id?>)" class="btn btn-sm btn-outline-danger"><?=$text_temp?></a>
                </td>
                <?php if ($current_status === 'inactive') { ?>
                <td class="delete_btn">
                  <a href="javascript:void(0)" onclick="deleteInstructor(this, <?=$instructor_id?>)" class="btn btn-sm btn-outline-danger">Delete</a>
                </td>
                <?php } ?>
              </tr>
            <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <?php if ($last_page >= 1 ) { ?>
    <div class="d-flex justify-content-center mt-3 border rounded p-2 bg-white shadow-sm">
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
              <a href="Instructors.php?status=<?=$current_status?>&page=<?=$prev?>&rows_per_page=<?=$row_num?>" class="btn btn-secondary m-2">Prev</a>
             <?php }else { ?>
              <a href="#" class="btn btn-secondary m-2 disabled">Prev</a>
             <?php 
             }
             $push_mid = $page;
             if ($page >= 2)  $push_mid = $page - 1;
             if ($page > 3)  $push_mid = $page - 3;
            
             for($i = $push_mid; $i < 5 + $page; $i++){
              if($i == $page){ ?>
               <a href="Instructors.php?status=<?=$current_status?>&page=<?=$i?>&rows_per_page=<?=$row_num?>" class="btn btn-success m-2"><?=$i?></a>
             <?php }else{ ?>
               <a href="Instructors.php?status=<?=$current_status?>&page=<?=$i?>&rows_per_page=<?=$row_num?>" class="btn btn-secondary m-2"><?=$i?></a>
             <?php } 
             if($last_page <= $i)break;
              } 
              if($next_btn){
              ?>
              <a href="Instructors.php?status=<?=$current_status?>&page=<?=$next?>&rows_per_page=<?=$row_num?>" class="btn btn-secondary m-2">Next</a>
          <?php }else { ?>
             <a href="#" class="btn btn-secondary m-2 disabled">Next</a>
          <?php } ?>
    </div>
    <?php } ?>
    <?php } else { ?>
      <div class="alert alert-info" role="alert">
        0 instructors record found in the database
      </div>
    <?php } ?>

</div>
 <!-- Footer -->
<?php include "inc/Footer.php"; ?>
<script src="../assets/js/jquery-3.5.1.min.js"></script>
<script type="text/javascript">
  var valu= "";
  var btext= "";
  var currentStatus = "<?=$current_status?>";

  function parseCount(text) {
    return parseInt(text.replace(/[^0-9]/g, ''), 10) || 0;
  }

  function updateTabCounts(oldStatus, newStatus) {
    var allCount = parseCount($("a[href='Instructors.php?status=all']").text());
    var activeCount = parseCount($("a[href='Instructors.php?status=active']").text());
    var inactiveCount = parseCount($("a[href='Instructors.php?status=inactive']").text());

    if (oldStatus === 'Active' && newStatus === 'Not Active') {
      activeCount = Math.max(activeCount - 1, 0);
      inactiveCount += 1;
    } else if (oldStatus === 'Not Active' && newStatus === 'Active') {
      inactiveCount = Math.max(inactiveCount - 1, 0);
      activeCount += 1;
    }

    // All count stays the same after status toggle
    $("a[href='Instructors.php?status=all']").text('All (' + allCount + ')');
    $("a[href='Instructors.php?status=active']").text('Active (' + activeCount + ')');
    $("a[href='Instructors.php?status=inactive']").text('Inactive (' + inactiveCount + ')');

    // Update the header count on current tab
    var currentCount = currentStatus === 'active' ? activeCount : currentStatus === 'inactive' ? inactiveCount : allCount;
    var headerText = currentStatus === 'all' ? 'All Instructors' : (currentStatus === 'active' ? 'Active Instructors' : 'Inactive Instructors');
    $("#instructor-list-title").text(headerText + ' (' + currentCount + ')');
  }

  function renumberRows() {
    $("table.table tbody tr").each(function(index) {
      $(this).find('td').first().text(index + 1);
    });
  }

  function updateCountsAfterDelete() {
    var allCount = Math.max(parseCount($("a[href='Instructors.php?status=all']").text()) - 1, 0);
    var inactiveCount = Math.max(parseCount($("a[href='Instructors.php?status=inactive']").text()) - 1, 0);
    $("a[href='Instructors.php?status=all']").text('All (' + allCount + ')');
    $("a[href='Instructors.php?status=inactive']").text('Inactive (' + inactiveCount + ')');
    if (currentStatus === 'inactive') {
      $("#instructor-list-title").text('Inactive Instructors (' + inactiveCount + ')');
    }
  }

  function deleteInstructor(current, inst_id){
    var row = $(current).closest('tr');
    if (!confirm('Delete this instructor permanently?')) {
      return;
    }

    $.post("Action/delete-instructor.php", {
      instructor_id: inst_id
    }, function(data, status){
      if (status === "success" && data == 1) {
        row.remove();
        renumberRows();
        updateCountsAfterDelete();
      } else {
        alert('Unable to delete instructor.');
      }
    });
  }

  function ChangeStatus(current, inst_id){
    var row = $(current).closest('tr');
    var cStatus = $.trim(row.find(".status").text());

    if (cStatus === "Active") {
      valu = "Not Active";
      btext = "Unblock";
    }
    else {
      valu = "Active";
      btext = "Block";
    }

    $.post("Action/active-instructor.php",
    {
      instructor_id: inst_id,
      val: valu
    },
    function(data, status){
      if (status == "success") {
        var oldStatus = cStatus;
        var newStatus = valu;
        var badgeClass = newStatus === 'Active' ? 'success' : 'secondary';
        row.find('.status').html('<span class="badge bg-' + badgeClass + '">' + newStatus + '</span>');
        $(current).text(btext);
        updateTabCounts(oldStatus, newStatus);

        if (currentStatus !== 'all') {
          var shouldRemove = (currentStatus === 'active' && newStatus === 'Not Active') ||
                             (currentStatus === 'inactive' && newStatus === 'Active');
          if (shouldRemove) {
            row.remove();
            renumberRows();
          }
        }
      }

    });
  }
</script>
<?php
 }else { 
$em = "First login ";
Util::redirect("../login.php", "error", $em);
} ?>