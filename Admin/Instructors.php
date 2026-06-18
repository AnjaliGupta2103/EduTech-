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

    $all_count = getCount('all');
    $active_count = getCount('active');
    $inactive_count = getCount('inactive');
    $row_count = getCount($current_status);

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
    $serial_start = $offset + 1;
    $instructors = getSomeInstructors($offset, $row_num, $current_status);
    # Header
    $title = "EduPulse - Instructors";
    include "inc/Header.php";
    
?>

<div class="container">
  <!-- NavBar -->
  <?php include "inc/NavBar.php"; ?>
  
  <div class="list-table pt-5">
  <ul class="nav nav-tabs mb-4">
    <li class="nav-item">
      <a class="nav-link <?= $current_status == 'all' ? 'active' : '' ?>" href="Instructors.php?status=all">All (<?=$all_count?>)</a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?= $current_status == 'active' ? 'active' : '' ?>" href="Instructors.php?status=active">Active (<?=$active_count?>)</a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?= $current_status == 'inactive' ? 'active' : '' ?>" href="Instructors.php?status=inactive">Inactive (<?=$inactive_count?>)</a>
    </li>
  </ul>
  <?php if ($instructors) { ?>
  <h4>
    <span id="instructor-list-title"><?= $current_status == 'all' ? 'All Instructors' : ($current_status == 'active' ? 'Active Instructors' : 'Inactive Instructors') ?> (<?=$row_count?>)</span>
    <?php if ($current_status == 'all') { ?>
      <a class="btn btn-success md-btn" href="Instructor-add.php">Add Instructor</a>
    <?php } ?>
  </h4>

  <table class="table table-bordered">
      <thead>
      <tr>
        <th>Id</th>
        <th>Full name</th>
        <th>Status</th>
        <th>Block/ Unblock</th>
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
       <td><a href="instructor.php?instructor_id=<?=$instructor_id?>"><?=$instructor["first_name"]?> <?=$instructor["last_name"]?></a></td>
       <td class="status"><?=$instructor["status"]?></td>
       <td class="action_btn">
        <a href="javascript:void(0)" 
           onclick="ChangeStatus(this, <?=$instructor_id?>)" 
           class="btn btn-danger"><?=$text_temp?></a>
       </td>
       <?php if ($current_status === 'inactive') { ?>
       <td class="delete_btn">
         <a href="javascript:void(0)" onclick="deleteInstructor(this, <?=$instructor_id?>)" class="btn btn-danger">Delete</a>
       </td>
       <?php } ?>
      </tr>
      <?php } ?>
      </tbody>
  </table>
  <?php if ($last_page > 1 ) { ?>
  <div class="d-flex justify-content-center mt-3 border">
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
            <a href="Instructors.php?status=<?=$current_status?>&page=<?=$prev?>" class="btn btn-secondary m-2">Prev</a>
           <?php }else { ?>
            <a href="#" class="btn btn-secondary m-2 disabled">Prev</a>
            
           <?php 
           }
           $push_mid = $page;
           if ($page >= 2)  $push_mid = $page - 1;
           if ($page > 3)  $push_mid = $page - 3;
          
           for($i = $push_mid; $i < 5 + $page; $i++){
            if($i == $page){ ?>
             <a href="Instructors.php?status=<?=$current_status?>&page=<?=$i?>" class="btn btn-success m-2"><?=$i?></a>
           <?php }else{ ?>
             <a href="Instructors.php?status=<?=$current_status?>&page=<?=$i?>" class="btn btn-secondary m-2"><?=$i?></a>

           <?php } 
           if($last_page <= $i)break;

            } 
            if($next_btn){
            ?>
            <a href="Instructors.php?status=<?=$current_status?>&page=<?=$next?>" class="btn btn-secondary m-2">Next</a>
        <?php }else { ?>
           <a href="#" class="btn btn-secondary m-2 disabled" des>Next</a>
        <?php } ?>
  </div>

  <?php }}else { ?>
    <div class="alert alert-info" role="alert">
      0 instructors record found in the database
</div>

  <?php } ?>
  </div>



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
        row.find(".status").text(newStatus);
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