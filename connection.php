<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'db_thesisched';
$port = '3306';

// TRY CONNECTING DATABASE
$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_errno) {
    die("gagal connect : " . $conn->connect_error);
}
