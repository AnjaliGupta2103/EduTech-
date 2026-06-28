<?php 
include "../Models/Student.php";
include "../Models/Certificate.php";
include "../Models/Course.php";
include "../Models/Instructor.php";
include "../Models/EnrolledStudent.php";
include "../Database.php";




function getstudentsCount(){
	$db = new Database();
      $db_conn = $db->connect();
	$student_models = new Student($db_conn);
	$res = $student_models->count();
	return $res;
}
function getEnrollmentCount(){
    $db = new Database();
    $db_conn = $db->connect();
    try {
        $sql = 'SELECT COUNT(enrolled_id) AS total FROM enrolled_student';
        $stmt = $db_conn->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? (int)$row['total'] : 0;
    } catch (PDOException $e) {
        return 0;
    }
}

function getCourseStatusCounts(){
    $db = new Database();
    $db_conn = $db->connect();
    try {
        $sql = 'SELECT status, COUNT(*) AS total FROM course GROUP BY status';
        $stmt = $db_conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    } catch (PDOException $e) {
        return [];
    }
}

function getTopCoursesByEnrollment($limit = 5){
    $db = new Database();
    $db_conn = $db->connect();
    try {
        $sql = 'SELECT c.course_id, c.title, COUNT(e.enrolled_id) AS enrollments
                FROM enrolled_student e
                JOIN course c ON c.course_id = e.course_id
                GROUP BY e.course_id
                ORDER BY enrollments DESC
                LIMIT :limit';
        $stmt = $db_conn->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
    } catch (PDOException $e) {
        return [];
    }
}

function getWeeklyStudentRegistrations($days = 7){
    $db = new Database();
    $db_conn = $db->connect();
    try {
        $interval = max(0, $days - 1);
        $sql = 'SELECT date_of_joined AS date, COUNT(*) AS count
                FROM student
                WHERE date_of_joined >= DATE_SUB(CURDATE(), INTERVAL :interval DAY)
                GROUP BY date_of_joined
                ORDER BY date_of_joined';
        $stmt = $db_conn->prepare($sql);
        $stmt->bindValue(':interval', (int)$interval, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stats = [];
        $start = new DateTime();
        $start->sub(new DateInterval('P' . $interval . 'D'));
        for ($i = 0; $i < $days; $i++) {
            $date = $start->format('Y-m-d');
            $stats[$date] = 0;
            $start->add(new DateInterval('P1D'));
        }

        foreach ($rows as $row) {
            $stats[$row['date']] = (int)$row['count'];
        }
        return $stats;
    } catch (PDOException $e) {
        return [];
    }
}

function getWeeklyCourseCreations($days = 7){
    $db = new Database();
    $db_conn = $db->connect();
    try {
        $interval = max(0, $days - 1);
        $sql = 'SELECT created_at AS date, COUNT(*) AS count
                FROM course
                WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL :interval DAY)
                GROUP BY created_at
                ORDER BY created_at';
        $stmt = $db_conn->prepare($sql);
        $stmt->bindValue(':interval', (int)$interval, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stats = [];
        $start = new DateTime();
        $start->sub(new DateInterval('P' . $interval . 'D'));
        for ($i = 0; $i < $days; $i++) {
            $date = $start->format('Y-m-d');
            $stats[$date] = 0;
            $start->add(new DateInterval('P1D'));
        }

        foreach ($rows as $row) {
            $stats[$row['date']] = (int)$row['count'];
        }
        return $stats;
    } catch (PDOException $e) {
        return [];
    }
}

function getRecentActivityCounts($days = 7){
    $db = new Database();
    $db_conn = $db->connect();
    try {
        $interval = max(0, $days - 1);
        $sql = 'SELECT
                    (SELECT COUNT(*) FROM student WHERE date_of_joined >= DATE_SUB(CURDATE(), INTERVAL :interval DAY)) AS new_students,
                    (SELECT COUNT(*) FROM course WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL :interval DAY)) AS new_courses,
                    (SELECT COUNT(*) FROM enrolled_student WHERE enrolled_at >= DATE_SUB(CURDATE(), INTERVAL :interval DAY)) AS new_enrollments';
        $stmt = $db_conn->prepare($sql);
        $stmt->bindValue(':interval', (int)$interval, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: ['new_students' => 0, 'new_courses' => 0, 'new_enrollments' => 0];
    } catch (PDOException $e) {
        return ['new_students' => 0, 'new_courses' => 0, 'new_enrollments' => 0];
    }
}
function getInstructorCount(){
	$db = new Database();
      $db_conn = $db->connect();
	$student_models = new Instructor($db_conn);
	$res = $student_models->count();
	return $res;
}

function getCourseCount(){
	$db = new Database();
      $db_conn = $db->connect();
	$student_models = new Course($db_conn);
	$res = $student_models->count();
	return $res;
}