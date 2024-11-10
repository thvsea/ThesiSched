<?php
include_once '../../../config.php';
require_once(LOCAL_ROOT . "/dashboard/middleware.php");
require_once(LOCAL_ROOT . "/connection.php");

$teacher_id = isset($_GET['teacher_id']) ? (int)$_GET['teacher_id'] : null;
if ($teacher_id == null) {
    echo "Teacher ID is missing!";
    exit;
}

// fetch data
$stmt = $conn->prepare("SELECT * FROM schedules WHERE lecturer_id = $teacher_id");
$stmt->execute();
$schedules = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$schedules_json = json_encode($schedules);

$stmt = $conn->prepare("SELECT * FROM guidances WHERE lecturer_id = $teacher_id AND status = 'Diterima'");
$stmt->execute();
$guidances = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$guidances_json = json_encode($guidances);

// page properties
$title = "Jadwal Dosen";
$stylePath = ROOT_PATH . "/style/style.css";
$scriptPath = ROOT_PATH . "/script/script.js";
//$hasSearch = false;
$headline = "Jadwal Dosen";
$headlineContent = null;


ob_start();
?>
<!-- content -->
<div class="p-4 mx-auto flex gap-8 xl:max-w-[90%]">
    <!-- calendar -->
    <div class="p-5 bg-white rounded-lg basis-1/3 space-y-5">
        <!-- navigation -->
        <div class="flex justify-between items-center">
            <p class="font-bold" id="month_display">
                <!-- BULAN DIISI LEWAT JS SCRIPT DIBAWAH -->
            </p>
            <div class="flex space-x-8">
                <button onclick="handlePrevMonth()" class="px-2 rounded-lg hover:bg-gray-100">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button onclick="handleNextMonth()" class="px-2 rounded-lg hover:bg-gray-100">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
        </div>
        <!-- day label -->
        <div class="grid grid-cols-7 text-center font-medium" id="day_labels">
        </div>
        <!-- dates -->
        <div class="grid grid-cols-7 " id="days_in_month">
            <!-- loop days -->
        </div>
    </div>
    <!-- time picker -->
    <div class="basis-2/3 space-y-8">
        <div class="h-fit grid grid-cols-4 gap-8">
            <?php
            $period = 1;
            for ($i = 7.5; $i <= 19.5; $i += 1) :
                $time = str_pad(intval($i), 2, '0', STR_PAD_LEFT) . ':' . ($i - intval($i) > 0 ? '30' : '00');
            ?>
                <input type="button" id="period-<?php echo $time; ?>-<?php echo str_pad(intval($i + 1), 2, '0', STR_PAD_LEFT) . ':' . ($i + 1 - intval($i + 1) > 0 ? '30' : '00'); ?>" value="<?php echo $time; ?>-<?php echo str_pad(intval($i + 1), 2, '0', STR_PAD_LEFT) . ':' . ($i + 1 - intval($i + 1) > 0 ? '30' : '00'); ?>" onclick="handleChooseTime(this)" class="period-button bg-white border border-black rounded-full p-4 text-center text-lg cursor-pointer transition-all hover:scale-95" data-period="<?php echo $period; ?>"></input>
            <?php
                $period++;
            endfor;
            ?>
        </div>
        <form method="POST">
            <input type="hidden" name="selected_date" id="selected_date">
            <input type="hidden" name="selected_time" id="selected_time">
            <button class="bg-[#78cf86] rounded-lg px-4 py-2 font-semibold transition-all hover:scale-95 hidden" id="request-button" name="request-button">REQUEST PERTEMUAN</button>
        </form>
    </div>
</div>
<?php
// convert content
$content = ob_get_clean();
// use template
include LOCAL_ROOT . '/templates/template.php';
?>
<script>
    const schedules = <?php echo $schedules_json; ?>;
    const guidances = <?php echo $guidances_json; ?>;
    // document ready
    (function() {
        const date = new Date();
        document.getElementById("selected_date").value = date.toISOString();
        document.getElementById("month_display").innerHTML = date.toLocaleDateString("id-ID", {
            month: "long",
            year: "numeric"
        })
        renderCalendar(date);
    })();

    // update display
    function updateMonthDisplay(date) {
        document.getElementById("selected_date").value = date.toISOString();
        document.getElementById("month_display").innerHTML = date.toLocaleDateString("id-ID", {
            month: "long",
            year: "numeric"
        });
    }

    // render calendar
    function renderCalendar(date) {
        const daysOfWeek = ["Mo", "Tu", "We", "Th", "Fr", "Sa", "Su"];
        const year = date.getFullYear();
        const month = date.getMonth();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const firstDay = new Date(year, month, 1).getDay();
        const startDay = (firstDay + 6) % 7;

        // Render day labels
        let dayLabelsHtml = '';
        for (let i = 0; i < 7; i++) {
            dayLabelsHtml += `<p>${daysOfWeek[(startDay + i) % 7]}</p>`;
        }
        document.getElementById("day_labels").innerHTML = dayLabelsHtml;
        let daysHtml = '';
        let currentDateId = '';
        for (let day = 1; day <= daysInMonth; day++) {
            const dayString = day.toString().padStart(2, '0');
            const monthString = (month + 1).toString().padStart(2, '0');
            const dateString = `${year}-${monthString}-${dayString}`;
            const disabled = (firstDay + (day - 1)) % 7 === 0 || (firstDay + (day - 1)) % 7 === 6;
            daysHtml += `<input type="button" ${disabled && "disabled"} value="${day}" onclick='handleSelectDate(this)' class="date-button ${disabled ? "bg-gray-100" : "cursor-pointer"} border aspect-square flex items-center justify-center hover:border-gray-400" id="${dateString}"/>`;
            if (day == date.getDate()) {
                currentDateId = dateString
            }
        }

        document.getElementById("days_in_month").innerHTML = daysHtml;
        document.getElementById(currentDateId).classList.add('!bg-[#45539d]', 'text-white');
        renderScheduleInDay(new Date(currentDateId));
    }

    function handleNextMonth() {
        clearCalendar();
        const current = new Date(document.getElementById("selected_date").value);
        current.setMonth(current.getMonth() + 1);
        updateMonthDisplay(current);
        renderCalendar(current);
        renderScheduleInDay(current);
        document.getElementById("request-button").classList.remove("hidden")
        const disabled = current.getDay() % 7 === 0 || current.getDay() % 7 === 6;
        if (current.getTime() < new Date().getTime() || disabled) {
            document.getElementById("request-button").classList.add("hidden")
        }
        document.getElementById("selected_date").value = current.toISOString();
    }

    function handlePrevMonth() {
        clearCalendar();
        const current = new Date(document.getElementById("selected_date").value);
        current.setMonth(current.getMonth() - 1);
        updateMonthDisplay(current);
        renderCalendar(current);
        renderScheduleInDay(current);

        document.getElementById("request-button").classList.remove("hidden")
        const disabled = current.getDay() % 7 === 0 || current.getDay() % 7 === 6;
        if (current.getTime() < new Date().getTime() || disabled) {
            document.getElementById("request-button").classList.add("hidden")
        }
        document.getElementById("selected_date").value = current.toISOString();
    }

    function isDateInPast(selectedDate, now) {
        if (selectedDate.getFullYear() < now.getFullYear()) {
            return true;
        }
        if (selectedDate.getFullYear() === now.getFullYear()) {
            if (selectedDate.getMonth() < now.getMonth()) {
                return true;
            }
            if (selectedDate.getMonth() === now.getMonth() && selectedDate.getDate() <= now.getDate()) {
                return true;
            }
        }
        return false;
    }

    function handleSelectDate(button) {
        clearCalendar();
        const now = new Date();
        const selectedDate = new Date(button.id);
        document.getElementById("selected_date").value = selectedDate.toISOString();
        document.getElementById("request-button").classList.remove("hidden")

        if (isDateInPast(selectedDate, now)) {
            document.getElementById("request-button").classList.add("hidden");
        }
        button.classList.add('!bg-[#45539d]', 'text-white');
        renderScheduleInDay(selectedDate);
    }

    function renderScheduleInDay(date) {
        // Check and disable periods based on selected day
        const dayIndex = date.getDay();
        schedules.forEach(schedule => {
            if (schedule.day === dayIndex) {
                const periodButton = document.querySelector(`.period-button[data-period='${schedule.time_period}']`);
                if (periodButton) {
                    periodButton.disabled = true;
                    periodButton.classList.add('!bg-[#f86d6d]', 'cursor-not-allowed');
                }
            }
        });
        guidances.forEach(guidance => {
            if (isDatesEquals(guidance.date, date)) {
                const periodButton = document.querySelector(`.period-button[data-period='${guidance.time_period}']`);
                if (periodButton) {
                    periodButton.disabled = true;
                    periodButton.classList.add('!bg-orange-200', 'cursor-not-allowed');
                }
            }
        });
    }

    function clearCalendar() {
        const dates = document.querySelectorAll('.date-button');
        const periods = document.querySelectorAll('.period-button');
        dates.forEach(btn => btn.classList.remove('!bg-[#45539d]', 'text-white'));
        periods.forEach(btn => btn.classList.remove('!bg-[#78cf86]', '!bg-[#f86d6d]', '!bg-orange-200', 'cursor-not-allowed'));
        periods.forEach(btn => btn.removeAttribute("disabled"));
        document.getElementById("selected_time").value = ""
    }

    function handleChooseTime(button) {
        const buttons = document.querySelectorAll('.period-button');
        buttons.forEach(btn => btn.classList.remove('!bg-[#78cf86]'));
        button.classList.add('!bg-[#78cf86]');
        document.getElementById("selected_time").value = button.getAttribute("data-period");
    }

    function isDatesEquals(d1, d2) {
        let date1 = new Date(d1).getTime();
        let date2 = new Date(d2).getTime();

        if (date1 == date2) {
            return true;
        }
        return false;
    };
</script>

<?php
if (isset($_POST["request-button"])) {
    $student = $_SESSION["user"];
    $selected_date = $_POST["selected_date"];
    $selected_time = $_POST["selected_time"];
    if (!$selected_time) {
        echo "<script> document.addEventListener('DOMContentLoaded', function() { showToast('Error', 'Waktu bimbingan harus dipilih!'); }); </script>";
        exit();
    }

    $date = new DateTime($selected_date);
    $formatted_date = $date->format('Y-m-d');
    $stmt = $conn->prepare("INSERT INTO guidances(student_id, lecturer_id, media, status, reason, date, time_period) VALUES(?,?,null,'Pending',null,?,?)");
    $stmt->bind_param("sssi", $student["id"], $teacher_id, $formatted_date, $selected_time);
    if ($stmt->execute()) {
        echo "<script> document.addEventListener('DOMContentLoaded', function() { showToast('Success', 'Request bimbingan berhasil dibuat!'); }); </script>";
        // echo "<script> window.location.replace('/proyek-adsi/dashboard/student/requested.php') </script>";
    } else {
        echo "<script> document.addEventListener('DOMContentLoaded', function() { showToast('Error', 'Terjadi kesalahan saat merequest bimbingan!'); }); </script>";
    }
    $stmt->close();
}
?>