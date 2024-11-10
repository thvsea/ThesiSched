<?php
session_start();
require_once("connection.php");
// redirect if there is session
if ($_SESSION && $_SESSION["user"]) {
    $user = $_SESSION["user"];
    if ($user["nim"]) {
        header("Location: /proyek-adsi/dashboard/student/teacher-list.php");
    } else {
        header("Location: /proyek-adsi/dashboard/teacher/schedule.php");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ThesiSched</title>
    <!-- tailwind cdn -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="./style/style.css">
    <!-- font-awesome icons -->
    <script src="https://kit.fontawesome.com/ee3dfa30d0.js" crossorigin="anonymous"></script>

    <script>
        function showToast(type, message) {
            const toastContainer = document.getElementById('toast-container');
            const toast = `<div class="flex items-center space-x-3 bg-white p-4 rounded-lg border border-gray-400">
                                <i class="fa-solid ${type == "Success" ? "fa-circle-check" : "fa-circle-xmark"} fa-lg ${type == "Success" ? "text-green-400" : "text-red-400"}"></i>
                                <div>
                                    <p>${type}</p>
                                    <p>${message}</p>
                                </div>
                                <button onclick="closeToast()"><i class="fa-solid fa-xmark"></i></button>
                            </div>`

            toastContainer.innerHTML += toast;

            setTimeout(function() {
                toastContainer.innerHTML = "";
            }, 3000);
        }

        function closeToast() {
            document.getElementById("toast-container").innerHTML = "";
        }

        function togglePassword() {
            const type = document.getElementById("passwordInput").getAttribute("type");
            if (type === "password") {
                document.getElementById("passwordInput").setAttribute("type", "text");
                document.getElementById("showPasswordButton").classList.add("hidden");
                document.getElementById("hidePasswordButton").classList.remove("hidden");
            } else {
                document.getElementById("passwordInput").setAttribute("type", "password");
                document.getElementById("hidePasswordButton").classList.add("hidden");
                document.getElementById("showPasswordButton").classList.remove("hidden");
            }
        }
    </script>
</head>

<body class="font-poppins-regular">
    <!-- toast -->
    <div class="fixed top-4 left-1/2 -translate-x-1/2 text-start" id="toast-container">

    </div>
    <!-- content -->
    <main class="bg-[#f4f4e2] min-h-dvh">
        <div class="max-w-[90%] sm:!max-w-[50%] lg:!max-w-[40%] xl:!max-w-[30%] mx-auto flex flex-col items-center py-12">
            <p class="text-5xl text-[#426b1f] font-newsreader-medium ">ThesiSched</p>
            <!-- form -->
            <form class="py-12 w-full space-y-5 text-start" method="POST">
                <p class="text-3xl font-poppins-medium">Log in</p>
                <!-- email or username -->
                <div class="space-y-1">
                    <p class="text-[#666666]">Email address or username</p>
                    <input type="text" name="email" placeholder="johndoe@gmail.com" class="border border-[#666666]/35 bg-transparent w-full p-3 rounded-lg" required>
                </div>
                <!-- password -->
                <div class="space-y-1">
                    <div class="flex justify-between">
                        <p class="text-[#666666]">Password</p>
                        <button type="button" id="hidePasswordButton" onclick="togglePassword()" class="text-[#666666] flex items-center space-x-2 hidden"><i class="fa-regular fa-eye-slash"></i><span>Hide</span></button>
                        <button type="button" id="showPasswordButton" onclick="togglePassword()" class="text-[#666666] flex items-center space-x-2"><i class="fa-regular fa-eye"></i><span>Show</span></button>
                    </div>
                    <input id="passwordInput" type="password" name="password" placeholder="&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;&#9679;" class="border border-[#666666]/35 bg-transparent w-full p-3 rounded-lg" required>
                </div>
                <!-- remember -->
                <div>
                    <input id="remember" name="remember" type="checkbox">
                    <label for="remember">Remember me</label>
                </div>
                <p>By continuing, you agree to the <span class="font-poppins-semibold underline">Terms of use</span> and <span class="font-poppins-semibold underline">Privacy Policy</span>. </p>
                <button class="w-full bg-[#b7b7a9] p-4 rounded-full text-white transition-all hover:scale-95 hover:bg-[#426b1f]/50" name="login">Log in</button>
                <div class="text-center">
                    <a href="./forgot-password.php" class="font-poppins-semibold underline">Forgot Password</a>
                </div>
            </form>
        </div>
    </main>
</body>

</html>

<?php
function unblockUser($conn, $user, $table)
{
    $stmt = $conn->prepare("UPDATE $table SET blocked = NULL, login_count = 0 WHERE id = ?");
    $stmt->bind_param("i", $user["id"]);
    $stmt->execute();
    $stmt->close();
}
function checkUserLogin($conn, $email, $password, $table)
{
    $stmt = $conn->prepare("SELECT * FROM $table WHERE email = ? OR username = ?");
    $stmt->bind_param("ss", $email, $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    // check user exist
    if (!$user) {
        if ($table == "lecturers") {
            echo "<script> document.addEventListener('DOMContentLoaded', function() { showToast('Error', 'User tidak ditemukan!'); }); </script>";
        }
        return null;
    }

    // check is blocked
    $blocked = $user["blocked"];
    if ($blocked) {
        $current_time = date("Y-m-d H:i:s");
        if ($current_time >= $blocked) {
            unblockUser($conn, $user, $table);
        } else {
            echo "<script> document.addEventListener('DOMContentLoaded', function() { showToast('Error', 'User ini sedang terblokir hingga $blocked'); }); </script>";
            $result = (object) [
                'status' => 403,
                'error' => 'User ini sedang terblokir',
            ];
            return $result;
        }
    }
    // password check
    if (!password_verify($password, $user["password"])) {
        $login_count = $user["login_count"] + 1;
        if ($login_count >= 3) {
            $blocked_until = date("Y-m-d H:i:s", strtotime('+1 day'));
            $stmt = $conn->prepare("UPDATE $table SET login_count = ?, blocked = ? WHERE id = ?");
            $stmt->bind_param("isi", $login_count, $blocked_until, $user["id"]);
        } else {
            $stmt = $conn->prepare("UPDATE $table SET login_count = ? WHERE id = ?");
            $stmt->bind_param("ii", $login_count, $user["id"]);
        }
        $stmt->execute();
        $stmt->close();
        echo "<script> document.addEventListener('DOMContentLoaded', function() { showToast('Error', 'Password tidak sesuai!'); }); </script>";
        $result = (object) [
            'status' => 403,
            'error' => 'Password tidak sesuai',
        ];
        return $result;
    }

    unblockUser($conn, $user, $table);
    echo "<script> document.addEventListener('DOMContentLoaded', function() { showToast('Success', 'Selamat datang $email!'); }); </script>";
    return $user;
}

if (isset($_POST["login"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $remember = isset($_POST["remember"]) ? $_POST["remember"] : null;
    $role = "student";
    $user = checkUserLogin($conn, $email, $password, 'students');

    // check if exist
    if (!$user) {
        $role = "teacher";
        $user = checkUserLogin($conn, $email, $password, 'lecturers');
        if (!$user) {
            return null;
        }
    }
    // error handling
    if($user["error"]){
        return null;
    }

    // success login
    $user['role'] = $role;
    $_SESSION['user'] = $user;

    if ($role == "student") {
        echo "<script>window.location.replace('/proyek-adsi/dashboard/student/schedule.php');</script>";
    } else {
        echo "<script>window.location.replace('/proyek-adsi/dashboard/teacher/schedule.php');</script>";
    }
    // setcookie("user-session", $user, time() + 5 * 60 * 60);
}

?>