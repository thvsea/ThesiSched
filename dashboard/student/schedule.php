<?php
include_once '../../config.php';
require_once(LOCAL_ROOT . "/dashboard/middleware.php");
require_once(LOCAL_ROOT . "/connection.php");
require_once(LOCAL_ROOT . "/student.php");

$user = $_SESSION["user"];
$student = new Student($conn, $user['id']);

$requests = $student->getSchedule();
$requests_json = json_encode($requests);

$title = "Jadwal Bimbingan";
$stylePath = ROOT_PATH . "/style/style.css";
$scriptPath = ROOT_PATH . "/script/script.js";
$headline = "Jadwal Bimbingan";
$headlineContent = null;
ob_start();
?>
<!-- content -->
<div class="p-4 mx-auto xl:max-w-[90%]">
    <div class="max-w-[50%] space-y-4">
        <?php
        $periods = ["07.30-08.30", "08.30-09.30", "09.30-10.30", "10.30-11.30", "11.30-12.30", "12.30-13.30", "13.30-14.30", "14.30-15.30", "15.30-16.30", "16.30-17.30", "17.30-18.30", "18.30-19.30", "19.30-20.30"];
        if (count($requests) > 0) {
            foreach ($requests as $key => $request) {
                $teacherName = $request["name"];
                $profilePicture = ROOT_PATH . "/assets/" . $request["image"];
                $guidanceMedia = $request["media"];

                $date = new DateTime($request["date"]);
                $formatter = new IntlDateFormatter('id_ID', IntlDateFormatter::FULL, IntlDateFormatter::NONE);
                $formatter->setPattern("EEEE, d MMMM yyyy");
                $guidanceDate = $formatter->format($date);
                $timePeriod = $periods[$request["time_period"] - 1];
                $status = $request["status"];
                $reason = $request["reason"];

                include LOCAL_ROOT . '/components/schedule-card.php';
            }
        } else {
        ?>
            <p>Anda belum memiliki jadwal bimbingan!</p>
        <?php
        }
        ?>
    </div>
</div>
<?php
$content = ob_get_clean();
include LOCAL_ROOT . '/templates/template.php';
?>