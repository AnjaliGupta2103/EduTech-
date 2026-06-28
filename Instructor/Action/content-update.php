<?php 
session_start();
include "../../Utils/Util.php";
if (isset($_SESSION['username']) &&
    isset($_SESSION['instructor_id'])) {
    include "../../Utils/Validation.php";
    include "../../Database.php";
    include "../../Models/Course.php";



   if ($_SERVER['REQUEST_METHOD'] == "POST") {
   $course_id = Validation::clean($_POST["course_id"]);
   $chapter_id = Validation::clean($_POST["chapter_id"]);
   $topic_id = Validation::clean($_POST["topic_id"]);
   $data = "";
   if (isset($_POST["text"])) {
       $data = $_POST["text"];
   }

   $db = new Database();
   $conn = $db->connect();
   $course = new Course($conn);
   $existingContent = $course->check_content([$course_id, $chapter_id, $topic_id]);

   if ($existingContent != 0) {
       $array_data = [$data, $course_id, $chapter_id, $topic_id];
       $course->update_content($array_data);
   } else {
       $array_data = [$course_id, $chapter_id, $topic_id, $data];
       $course->insert_content($array_data);
   }

   $_SESSION['content'] = "0,$topic_id,$chapter_id,$course_id";
   
   Util::redirect("../Courses-content-edit-page.php", "success", "Updated content successfully", "course_id=$course_id&chapter_id=$chapter_id&topic_id=$topic_id");
}
}else {
      
    $em = "First login ";
    Util::redirect("../../login.php", "error", $em);
}



