<?php 
session_start();
include "../Utils/Util.php";
if (isset($_SESSION['username']) &&
    isset($_SESSION['admin_id'])) {
    include "../Controller/Admin/Student.php";
    $row_count = getCount();

    $page = 1;
    $allowed_rows = [5, 10, 20, 50];
    $row_num = 5;
    if (isset($_GET['rows_per_page']) && in_array((int) $_GET['rows_per_page'], $allowed_rows, true)) {
        $row_num = (int) $_GET['rows_per_page'];
    }

    $offset = 0;
    $last_page = max(1, ceil($row_count / $row_num));
    if(isset($_GET['page'])){
        $page = (int) $_GET['page'];
        if($page > $last_page){
            $page = $last_page;
        } else if($page <= 0){
            $page = 1; 
        }
    }
    if($page != 1) $offset = ($page-1) * $row_num;
    $start_record = $row_count > 0 ? $offset + 1 : 0;
    $end_record = min($offset + $row_num, $row_count);
    $students = getSomeStudent($offset, $row_num);
    # Header
    $title = "EduPulse - Students ";
    include "inc/Header.php";

?>

<div class="container">
  <!-- NavBar -->
  <?php include "inc/NavBar.php"; ?>
  
  <div class="list-table pt-5">
    <?php if ($students) { ?>
    <div class="card shadow-sm border-0 admin-list-card mb-4">
      <div class="card-body">
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between mb-4 gap-3">
          <div>
            <h4 class="mb-1">All Students <span class="text-muted">(<?=$row_count?>)</span></h4>
            <div class="text-muted small">Showing <?=$start_record?> to <?=$end_record?> of <?=$row_count?> students</div>
          </div>
          <form method="get" class="d-flex align-items-center gap-2 mb-0">
            <label class="mb-0 small text-muted">Records per page</label>
            <select name="rows_per_page" class="form-select form-select-sm" onchange="this.form.submit()">
              <?php foreach ($allowed_rows as $num) { ?>
                <option value="<?=$num?>" <?= $row_num === $num ? 'selected' : '' ?>><?=$num?></option>
              <?php } ?>
            </select>
            <noscript><button type="submit" class="btn btn-primary btn-sm">Apply</button></noscript>
          </form>
        </div>
        <div class="table-responsive">
          <table class="table table-hover table-bordered align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>Id</th>
                <th>Full name</th>
                <th>Status</th>
                <th>Block / Unblock</th>
              </tr>
            </thead>
            <tbody>
            <?php foreach ($students as $student) {?>
              <tr>
                <td><?=$student["student_id"]?></td>
                <td><a class="text-decoration-none" href="Student.php?student_id=<?=$student["student_id"]?>"><?=$student["first_name"]?> <?=$student["last_name"]?></a></td>
                <td class="status">
                  <span class="badge bg-<?= $student["status"] == "Active" ? 'success' : 'secondary' ?>"><?=$student["status"]?></span>
                </td>
                <td class="action_btn">
                  <?php  
                  $student_id = $student["student_id"];
                  $text_temp = $student["status"] == "Active" ? "Block": "Unblock";
                  ?>
                  <a href="javascript:void(0)" onclick="ChangeStatus(this, <?=$student_id?>)" class="btn btn-sm btn-outline-danger"><?=$text_temp?></a>
                </td>
              </tr>
            <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <?php if ($last_page > 1 ) { ?>
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
            <a href="index.php?page=<?=$prev?>&rows_per_page=<?=$row_num?>" class="btn btn-secondary m-2">Prev</a>
           <?php }else { ?>
            <a href="#" class="btn btn-secondary m-2 disabled">Prev</a>
            
           <?php 
           }
           $push_mid = $page;
           if ($page >= 2)  $push_mid = $page - 1;
           if ($page > 3)  $push_mid = $page - 3;
          
           for($i = $push_mid; $i < 5 + $page; $i++){
            if($i == $page){ ?>
             <a href="index.php?page=<?=$i?>&rows_per_page=<?=$row_num?>" class="btn btn-success m-2"><?=$i?></a>
           <?php }else{ ?>
             <a href="index.php?page=<?=$i?>&rows_per_page=<?=$row_num?>" class="btn btn-secondary m-2"><?=$i?></a>

           <?php } 
           if($last_page <= $i)break;

            } 
            if($next_btn){
            ?>
            <a href="index.php?page=<?=$next?>&rows_per_page=<?=$row_num?>" class="btn btn-secondary m-2">Next</a>
        <?php }else { ?>
           <a href="#" class="btn btn-secondary m-2 disabled" des>Next</a>
        <?php } ?>
  </div>

  <?php }}else { ?>
    <div class="alert alert-info" role="alert">
      0 students record found in the database
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
  function ChangeStatus(current, stud_id){
    var cStatus = $(current).parent().parent().children(".status").text().toString();
   
    if (cStatus == "Active") {
      valu = "Not Active";
      btext = "Unblock";
    }
    else {
      valu= "Active";
      btext = "Block"; 
    }

    $.post("Action/active-student.php",
    {
      student_id: stud_id,
      val: valu
    },
    function(data, status){
      if (status == "success") {
        var statusCell = $(current).closest('tr').find('.status');
        statusCell.html('<span class="badge bg-' + (valu == 'Active' ? 'success' : 'secondary') + '">' + valu + '</span>');
        $(current).parent().children('a').text(btext);
      }

    });
  }
</script>
<?php
 }else { 
$em = "First login ";
Util::redirect("../login.php", "error", $em);
} ?>