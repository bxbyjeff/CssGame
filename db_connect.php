<?php
$servername = "localhost";
$username = "root"; // ค่าเริ่มต้นของ XAMPP
$password = ""; // ค่าเริ่มต้นของ XAMPP
$dbname = "css_game";

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("การเชื่อมต่อล้มเหลว: " . $conn->connect_error);
}
?>
