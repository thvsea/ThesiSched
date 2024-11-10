<?php
session_start();
$user = $_SESSION["user"];
if (!$user) {
    unset($_SESSION['user']);
    session_destroy();
    header("Location: /proyek-adsi/index.php");
    exit();
}
$role = $user["role"];
$actual_link = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$path_components = explode('/', parse_url($actual_link, PHP_URL_PATH));

if (($role === 'student' && !in_array('student', $path_components)) ||  ($role === 'teacher' && !in_array('teacher', $path_components))) {
    header("Location: /proyek-adsi/dashboard/forbidden.php");
    exit();
}
