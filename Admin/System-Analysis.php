<?php 
session_start();
include "../Utils/Util.php";
include "../Utils/Validation.php";
if (isset($_SESSION['username']) &&
    isset($_SESSION['admin_id'])) {
    
    include "../Controller/Admin/System.php";  
    // get analytics metrics
    $student_count = getstudentsCount();
    $Instructor_count = getInstructorCount();
    $Course_count = getCourseCount();
    $enrollment_count = getEnrollmentCount();
    $top_courses = getTopCoursesByEnrollment(3);
    $weekly_student_registrations = getWeeklyStudentRegistrations(7);
    $weekly_course_creations = getWeeklyCourseCreations(7);
    $recent_activity = getRecentActivityCounts(7);

    $registrationLabels = json_encode(array_keys($weekly_student_registrations));
    $registrationCounts = json_encode(array_values($weekly_student_registrations));
    $courseCreationLabels = json_encode(array_keys($weekly_course_creations));
    $courseCreationCounts = json_encode(array_values($weekly_course_creations));
    $topCourseTitles = json_encode(array_column($top_courses, 'title'));
    $topCourseEnrollments = json_encode(array_column($top_courses, 'enrollments'));
    $statusLabels = json_encode(array_map(function($row){ return $row['status']; }, $course_status_counts = getCourseStatusCounts()));
    $statusCounts = json_encode(array_map(function($row){ return (int)$row['total']; }, $course_status_counts));
    
    # Header 
    $title = "EduPulse - System Analysis ";
    include "inc/Header.php";
?>
<div class="container">
  <!-- NavBar -->
  <?php include "inc/NavBar.php"; ?>
  
  <div class="p-5 shadow">
    <h4>System Analysis</h4><hr><br>

    <style>
      .chart-container canvas { display: block; width: 100%; height: 100%; }
    </style>

    <div class="row gy-4">
      <div class="col-12">
        <div class="card p-4 mb-4" style="min-height: 360px;">
          <h5 class="mb-3">Student Registrations</h5>
          <div class="chart-container" style="position: relative; height: 340px; width: 100%;">
            <canvas id="registrationChart"></canvas>
          </div>
        </div>
      </div>
      <div class="col-12">
        <div class="card p-4 mb-4" style="min-height: 360px;">
          <h5 class="mb-3">Course Creations</h5>
          <div class="chart-container" style="position: relative; height: 340px; width: 100%;">
            <canvas id="courseCreationChart"></canvas>
          </div>
        </div>
      </div>
      <div class="col-12">
        <div class="card p-4 mb-4" style="min-height: 360px;">
          <h5 class="mb-3">Course Visibility</h5>
          <div class="chart-container" style="position: relative; height: 340px; width: 100%;">
            <canvas id="courseStatusChart"></canvas>
          </div>
        </div>
      </div>
    </div>

    <div class="row gy-4 mt-2">
      <div class="col-12 col-lg-6">
        <div class="card p-4 h-100">
          <h5 class="mb-3">Overall Statistics</h5>
          <ul class="d-flex flex-wrap gap-3 list-unstyled mb-0">
              <li class="border rounded p-3 text-center flex-fill" style="min-width: 170px;"><span class="d-block fs-3 fw-bold"><?=$student_count?></span>Total Students</li>
              <li class="border rounded p-3 text-center flex-fill" style="min-width: 170px;"><span class="d-block fs-3 fw-bold"><?=$Instructor_count?></span>Total Instructors</li>
              <li class="border rounded p-3 text-center flex-fill" style="min-width: 170px;"><span class="d-block fs-3 fw-bold"><?=$Course_count?></span>Total Courses</li>
              <li class="border rounded p-3 text-center flex-fill" style="min-width: 170px;"><span class="d-block fs-3 fw-bold"><?=$enrollment_count?></span>Total Enrollments</li>
          </ul>
        </div>
      </div>
      <div class="col-12 col-lg-6">
        <div class="card p-4 h-100">
          <h5 class="mb-3">Recent Activity</h5>
          <ul class="list-unstyled mb-0">
              <li class="mb-2"><?= htmlspecialchars($recent_activity['new_students']) ?> new students joined in the last 7 days.</li>
              <li class="mb-2"><?= htmlspecialchars($recent_activity['new_courses']) ?> new courses were created in the last 7 days.</li>
              <li class="mb-2"><?= htmlspecialchars($recent_activity['new_enrollments']) ?> new enrollments happened in the last 7 days.</li>
          </ul>
        </div>
      </div>
    </div>

    <div class="row gy-4 mt-4">
      <div class="col-12">
        <div class="card p-4">
          <h5 class="mb-3">Top Courses by Enrollment</h5>
          <ul class="list-group">
              <?php if (!empty($top_courses)) { ?>
                  <?php foreach ($top_courses as $course) { ?>
                      <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?= htmlspecialchars($course['title']) ?>
                        <span class="badge bg-primary rounded-pill"><?= htmlspecialchars($course['enrollments']) ?> students</span>
                      </li>
                  <?php } ?>
              <?php } else { ?>
                  <li class="list-group-item">No enrollments recorded yet.</li>
              <?php } ?>
          </ul>
        </div>
      </div>
    </div>
  </div>

<script>
    var registrationLabels = <?=$registrationLabels?>;
    var registrationCounts = <?=$registrationCounts?>;
    var courseCreationLabels = <?=$courseCreationLabels?>;
    var courseCreationCounts = <?=$courseCreationCounts?>;
    var statusLabels = <?=$statusLabels?>;
    var statusCounts = <?=$statusCounts?>;

    var registrationChart = new Chart(document.getElementById('registrationChart'), {
        type: 'line',
        data: {
            labels: registrationLabels,
            datasets: [{
                label: 'New student registrations',
                data: registrationCounts,
                backgroundColor: 'rgba(54, 162, 235, 0.15)',
                borderColor: 'rgba(54, 162, 235, 1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    var courseCreationChart = new Chart(document.getElementById('courseCreationChart'), {
        type: 'bar',
        data: {
            labels: courseCreationLabels,
            datasets: [{
                label: 'New courses created',
                data: courseCreationCounts,
                backgroundColor: 'rgba(40, 167, 69, 0.3)',
                borderColor: 'rgba(40, 167, 69, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            devicePixelRatio: window.devicePixelRatio || 1,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    var courseStatusChart = new Chart(document.getElementById('courseStatusChart'), {
        type: 'doughnut',
        data: {
            labels: statusLabels,
            datasets: [{
                data: statusCounts,
                backgroundColor: ['#0d6efd', '#6c757d', '#198754', '#ffc107'],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            devicePixelRatio: window.devicePixelRatio || 1,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
</script>
</div>
 <!-- Footer -->
<?php include "inc/Footer.php"; ?>


<?php
 }else { 
$em = "First login ";
Util::redirect("../login.php", "error", $em);
} ?>