<?php 
include "../Models/Instructor.php";
include "../Models/Certificate.php";
include "../Models/Course.php";

include "../Database.php";


function normalizeInstructorStatus($status){
    $status = strtolower(trim($status));
    if ($status === 'active' || $status === 'inactive' || $status === 'all') {
        return $status;
    }
    return 'all';
}

function getSomeInstructors($offset, $num, $status = 'all'){

    $db = new Database();
      $db_conn = $db->connect();
    $student_models = new Instructor($db_conn);

    $data = $student_models->getSomeByStatus($offset, $num, normalizeInstructorStatus($status));
    
    return $data;
}

function getCount($status = 'all'){
    $db = new Database();
      $db_conn = $db->connect();
    $student_models = new Instructor($db_conn);
    $res = $student_models->countByStatus(normalizeInstructorStatus($status));
	return $res;
}

function getById($instructor_id){
	$db = new Database();
    $db_conn = $db->connect();
	$student = new Instructor($db_conn);
	$student->init($instructor_id);
	return $student->getData();
}

function getCourseById($instructor_id){
	$db = new Database();
    $db_conn = $db->connect();
    $course_model = new Course($db_conn);
	$courses = $course_model->getByInstructorId($instructor_id);
	return $courses;
}
