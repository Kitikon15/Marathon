<?php
$host = "sql305.infinityfree.com";
$user = "if0_40745154";
$pass = "kitikon15";
$dbname = "if0_40745154_kitikon"; // ชื่อฐานข้อมูลของคุณ

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// ตั้งค่าภาษาไทย
mysqli_set_charset($conn, "utf8");
?>