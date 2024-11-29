<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Adventure Game</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="register-container">
        <div class="register-box">
            <h2>สมัครสมาชิก</h2>
            <form action="register_action.php" method="POST">
                <div class="input-group">
                    <label for="username">ชื่อผู้ใช้</label>
                    <input type="text" name="username" id="username" placeholder="กรอกชื่อผู้ใช้" required>
                </div>
                <div class="input-group">
                    <label for="password">รหัสผ่าน</label>
                    <input type="password" name="password" id="password" placeholder="กรอกรหัสผ่าน" required>
                </div>
                <div class="input-group">
                    <label for="confirm_password">ยืนยันรหัสผ่าน</label>
                    <input type="password" name="confirm_password" id="confirm_password" placeholder="ยืนยันรหัสผ่าน" required>
                </div>
                <button type="submit" class="btn">สมัครสมาชิก</button>
                <p>มีบัญชีอยู่แล้ว? <a href="login.php">เข้าสู่ระบบ</a></p>
            </form>
        </div>
    </div>
</body>
</html>
