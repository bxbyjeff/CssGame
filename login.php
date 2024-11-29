<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Adventure Game</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h2>เข้าสู่ระบบ</h2>
            <form action="login_action.php" method="POST">
                <div class="input-group">
                    <label for="username">ชื่อผู้ใช้</label>
                    <input type="text" name="username" id="username" placeholder="กรอกชื่อผู้ใช้" required>
                </div>
                <div class="input-group">
                    <label for="password">รหัสผ่าน</label>
                    <input type="password" name="password" id="password" placeholder="กรอกรหัสผ่าน" required>
                </div>
                <button type="submit" class="btn">เข้าสู่ระบบ</button>
                <p>ยังไม่มีบัญชี? <a href="register.php">สมัครสมาชิก</a></p>
            </form>
        </div>
    </div>
</body>
</html>
