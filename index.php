<?php
session_start();
if (isset($_SESSION['username'])) {
    header("Location: game.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSS Game</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>ยินดีต้อนรับสู่เกม CSS!</h1>
    <a href="login.php">เข้าสู่ระบบ</a> | <a href="register.php">สมัครสมาชิก</a>
</body>
</html>

