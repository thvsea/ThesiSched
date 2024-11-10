<?php
include_once '../../config.php';
require_once(LOCAL_ROOT . "/dashboard/middleware.php");
require_once(LOCAL_ROOT . "/connection.php");
require_once(LOCAL_ROOT . "/Student.php");

$user = $_SESSION["user"];
$student = new Student($conn, $user['id']);

$lecturers = $student->getLecturers();

// Page properties
$title = "List Dosen";
$stylePath = ROOT_PATH . "/style/style.css";
$scriptPath = ROOT_PATH . "/script/script.js";
//$hasSearch = true;
$headline = "List Dosen";
$headlineContent = null;

ob_start();
?>
<!-- content -->
<div class="p-4 mx-auto xl:max-w-[90%]">
    <div class="grid grid-cols-5 gap-8">
        <?php
        foreach ($lecturers as $key => $lecturer) {
            $teacherId = $lecturer["id"];
            $teacherName = $lecturer["name"];
            $profilePicture = ROOT_PATH . "/assets" . $lecturer["image"];
            include LOCAL_ROOT . '/components/teacher-card.php';
        }
        ?>
    </div>
</div>
<?php
$content = ob_get_clean();
include LOCAL_ROOT . '/templates/template.php';
?>