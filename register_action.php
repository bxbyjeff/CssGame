<?php
session_start(); // เริ่มต้น session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าจากฟอร์ม
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // ตรวจสอบว่าข้อมูลไม่เว้นว่าง
    if (empty($username) || empty($password) || empty($confirm_password)) {
        die("กรุณากรอกข้อมูลให้ครบถ้วน");
    }

    // ตรวจสอบว่ารหัสผ่านตรงกัน
    if ($password !== $confirm_password) {
        die("รหัสผ่านและการยืนยันรหัสผ่านไม่ตรงกัน");
    }

    // เชื่อมต่อฐานข้อมูล
    $servername = "localhost";
    $db_username = "root";
    $db_password = ""; // แก้ไขหากคุณตั้งรหัสผ่านไว้
    $dbname = "css_game";

    $conn = new mysqli($servername, $db_username, $db_password, $dbname);

    // ตรวจสอบการเชื่อมต่อฐานข้อมูล
    if ($conn->connect_error) {
        die("การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
    }

    // เข้ารหัสรหัสผ่าน
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // ตรวจสอบว่าชื่อผู้ใช้มีอยู่แล้วหรือไม่
    $check_user_stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $check_user_stmt->bind_param("s", $username);
    $check_user_stmt->execute();
    $check_user_stmt->store_result();

    if ($check_user_stmt->num_rows > 0) {
        die("ชื่อผู้ใช้นี้ถูกใช้แล้ว");
    }
    $check_user_stmt->close();

    // บันทึกข้อมูลลงในตาราง users
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashed_password);

    if ($stmt->execute()) {
        echo "สมัครสมาชิกสำเร็จ! <a href='login.php'>เข้าสู่ระบบ</a>";
    } else {
        echo "เกิดข้อผิดพลาด: " . $stmt->error;
    }

    // ปิดการเชื่อมต่อ
    $stmt->close();
    $conn->close();
}
?>
