<?php 
session_start();
include "../../Utils/Util.php";
include "../../Utils/Validation.php";

if (isset($_SESSION['username']) && isset($_SESSION['instructor_id'])) {
    include "../../Database.php";
    include "../../Models/Course.php";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        $course_id = isset($_POST['course_id']) ? (int)$_POST['course_id'] : 0;
        $title = Validation::clean($_POST['title'] ?? '');
        $description = Validation::clean($_POST['description'] ?? '');
        $instructor_id = $_SESSION['instructor_id'];

        if (!$course_id || empty($title) || empty($description)) {
            Util::redirect("../Course-edit.php", "error", "Please provide valid course details", "course_id=" . $course_id);
        }

        $cover = "default_course.jpg";
        if (isset($_FILES['cover']['name']) && !empty($_FILES['cover']['name'])) {
            $img_name = $_FILES['cover']['name'];
            $tmp_name = $_FILES['cover']['tmp_name'];
            $error = $_FILES['cover']['error'];

            if ($error === 0) {
                $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                $img_ex_to_lc = strtolower($img_ex);
                $allowed_exs = array('jpg', 'jpeg', 'png');

                if (in_array($img_ex_to_lc, $allowed_exs)) {
                    $new_img_name = uniqid("COVER-", true) . '.' . $img_ex_to_lc;
                    $img_upload_path = '../../Upload/thumbnail/' . $new_img_name;
                    move_uploaded_file($tmp_name, $img_upload_path);
                    $cover = $new_img_name;
                } else {
                    Util::redirect("../Course-edit.php", "error", "You can't upload files of this type", "course_id=" . $course_id);
                }
            } else {
                Util::redirect("../Course-edit.php", "error", "Unknown upload error", "course_id=" . $course_id);
            }
        }

        $db = new Database();
        $conn = $db->connect();
        $course_model = new Course($conn);

        $sql = 'UPDATE course SET title = ?, description = ?, cover = ? WHERE course_id = ? AND instructor_id = ?';
        $stmt = $conn->prepare($sql);
        $res = $stmt->execute([$title, $description, $cover, $course_id, $instructor_id]);

        if ($res) {
            Util::redirect("../Course-edit.php", "success", "Course updated successfully", "course_id=" . $course_id);
        } else {
            Util::redirect("../Course-edit.php", "error", "Unable to update course", "course_id=" . $course_id);
        }
    } else {
        Util::redirect("../Course-edit.php", "error", "Request method error", "course_id=" . ($course_id ?? 0));
    }
} else {
    $em = "First login ";
    Util::redirect("../../login.php", "error", $em);
}
