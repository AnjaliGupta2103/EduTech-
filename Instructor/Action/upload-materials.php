<?php 
session_start();
include "../../Utils/Util.php";
include "../../Utils/Validation.php";

if (isset($_SESSION['username']) &&
    isset($_SESSION['instructor_id'])) {
    
   if (isset($_FILES['file']['name'])) {
      include "../../Database.php";
      include "../../Models/CoursesMaterial.php";
     
       $db = new Database();
       $conn = $db->connect();

       $courses_material = new CoursesMaterial($conn);
  
       $instructor_id = $_SESSION['instructor_id'];

       $VIDEO = array('mp4', 'mkv', 'avi', 'mov', 'webm');
       $IMAGE = array('jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp');
       $PDF = array('pdf');
       $DOCS = array('docx', 'doc', 'pptx', 'ppt', 'xlsx', 'xls', 'txt', 'csv', 'md', 'json');
       $ARCHIVE = array('zip', 'rar', '7z');
       $type = "";

      $file_name = $_FILES['file']['name'];
      $file_tmp_name = $_FILES['file']['tmp_name'];
      $file_upload_error = $_FILES['file']['error'];

      if($file_upload_error === 0){
           $file_ex = pathinfo($file_name, PATHINFO_EXTENSION);
           $file_ex_to_lc = strtolower($file_ex);
           if(in_array($file_ex_to_lc, $VIDEO)){
              $type = "Video";
           }else if(in_array($file_ex_to_lc, $IMAGE)){
              $type = "Picture";
           }else if(in_array($file_ex_to_lc, $PDF)){
              $type = "PDF";
           }else if(in_array($file_ex_to_lc, $DOCS)){
              $type = "DOCS";
           }else if(in_array($file_ex_to_lc, $ARCHIVE)){
              $type = "Archive";
           }else {
               $em = "You can't upload files of this type";
                Util::redirect("../Courses-Materials-add.php", "error", $em);
            }

            $target_dir = '../../Upload/CoursesMaterials/'.$type;
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $new_file_name = uniqid("Material-$type", true).'.'.$file_ex_to_lc;
            $file_upload_path = $target_dir.'/'.$new_file_name;
            $URL = '../Upload/CoursesMaterials/'.$type.'/'.$new_file_name;
            if (move_uploaded_file($file_tmp_name, $file_upload_path)) {
                // add to the Database
                $res = $courses_material->insert($instructor_id, $URL, $type);
                if ($res) {
                   $sm = "New Courses Material Uploaded!";
                   Util::redirect("../Courses-Materials-add.php", "success", $sm);
                }else {
                    $em = "unknown error occurred!";
                     Util::redirect("../Courses-Materials-add.php", "error", $em);
                }
            } else {
                $em = "Unable to save the uploaded file.";
                Util::redirect("../Courses-Materials-add.php", "error", $em);
            }
            
      }else {
         $em = "unknown error occurred!";
         Util::redirect("../Courses-Materials-add.php", "error", $em);
      }
      
      

   }else { 
      $em = "unknown error occurred!";
           Util::redirect("../Courses-Materials-add.php", "error", $em);
   } 
}else { 
   $em = "First login ";
   Util::redirect("../../login.php", "error", $em);
} 
