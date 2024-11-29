<?php
session_start(); // เริ่มต้น session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับค่าจากฟอร์ม
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // เชื่อมต่อฐานข้อมูล
    $servername = "localhost";
    $db_username = "root";
    $db_password = "";
    $dbname = "css_game";

    $conn = new mysqli($servername, $db_username, $db_password, $dbname);

    // ตรวจสอบการเชื่อมต่อ
    if ($conn->connect_error) {
        die("การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
    }

    // ดึงข้อมูลผู้ใช้จากฐานข้อมูล
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();

        // ตรวจสอบรหัสผ่าน
        if (password_verify($password, $hashed_password)) {
            // บันทึกสถานะการล็อกอินใน Session
            $_SESSION['user_id'] = $user_id; // ID ผู้ใช้
            $_SESSION['username'] = $username; // ชื่อผู้ใช้

            // เปลี่ยนเส้นทางไปยังหน้า game.php
            header("Location: game.php");
            exit();
        } else {
            echo "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
        }
    } else {
        echo "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
    }

    // ปิดการเชื่อมต่อ
    $stmt->close();
    $conn->close();
}
?>
