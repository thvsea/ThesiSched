<?php
// Get the current page's filename
$current_page = basename($_SERVER['PHP_SELF']);
$user = $_SESSION["user"];

if (isset($_GET["logout"])) {
    unset($_SESSION['user']);
    session_destroy();
    header("Location: /proyek-adsi/index.php");
    exit();
}
?>
<nav class="py-4 bg-[#f4f4e2] w-full">
    <div class="flex w-full justify-between px-4 mx-auto xl:max-w-[90%]">
        <p class="text-4xl text-[#426b1f] font-newsreader-medium">ThesiSched</p>
        <div class="flex space-x-5 items-center">
            <?php
            if ($user["role"] == "student") {
            ?>
                <a href="/proyek-adsi/dashboard/student/teacher-list.php" class="<?php echo $current_page == 'teacher-list.php' ? 'font-bold' : ''; ?>">List Dosen</a>
            <?php
            } else {
            ?>
                <a href="/proyek-adsi/dashboard/teacher/input-schedule.php" class="<?php echo $current_page == 'input-schedule.php' ? 'font-bold' : ''; ?>">Input Jadwal</a>
            <?php
            }
            ?>
            <a href="/proyek-adsi/dashboard/<?php echo $user["role"] ?>/requested.php" class="<?php echo $current_page == 'requested.php' ? 'font-bold' : ''; ?>">Requested</a>
            <a href="/proyek-adsi/dashboard/<?php echo $user["role"] ?>/schedule.php" class="<?php echo $current_page == 'schedule.php' ? 'font-bold' : ''; ?>">Jadwal Bimbingan</a>

            <div class="border border-gray-400 h-full"></div>
            <div class="relative group">
                <?php
                if ($user["role"] == "student") {
                ?>
                    <div class="w-[40px] h-[40px] shrink-0 bg-[#d9dce1] flex justify-center items-center rounded-full">
                        <i class="fa-solid fa-user  text-[#72777b]"></i>
                    </div>
                <?php
                } else {
                ?>
                    <img src="<?php echo ROOT_PATH . "/assets/" . $user["image"] ?>" alt="" class="w-10 h-10 rounded-full">
                <?php
                }
                ?>
                <div class="pt-2 absolute right-0 text-start leading-none hidden group-hover:block">
                    <div class="bg-white p-4 rounded-lg min-w-64 space-y-2">
                        <div class="flex items-center space-x-3">
                            <?php
                            if ($user["role"] == "student") {
                            ?>
                                <div class="w-[40px] h-[40px] shrink-0 bg-[#d9dce1] flex justify-center items-center rounded-full">
                                    <i class="fa-solid fa-user  text-[#72777b]"></i>
                                </div>
                            <?php
                            } else {
                            ?>
                                <img src="<?php echo ROOT_PATH . "/assets/" . $user["image"] ?>" alt="" class="w-10 h-10 rounded-full">
                            <?php
                            }
                            ?>
                            <div class="leading-none">
                                <p class="font-inter-semibold"><?php echo $user["name"] ?></p>
                                <p><?php echo $user["role"] ?></p>
                                <p><?php
                                    if ($user["role"] == "student") {
                                        echo $user["nim"];
                                    } else {
                                        echo $user["nidn"];
                                    }
                                    ?></p>
                            </div>
                        </div>
                        <form method="GET">
                            <input type="submit" name="logout" value="Logout" class="w-full cursor-pointer bg-[#b7b7a9] px-4 py-2 rounded-lg text-white transition-all hover:scale-95 hover:bg-red-500" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>