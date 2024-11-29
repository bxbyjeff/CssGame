<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION['health'])) {
    $_SESSION['health'] = 100;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSS Adventure Game - Level 7</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Kanit', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #1a1c2c 0%, #4a1942 100%);
            color: #fff;
            min-height: 100vh;
        }

        .game-container {
            display: flex;
            height: 100vh;
            position: relative;
            overflow: hidden;
        }

        .game-info {
            width: 30%;
            padding: 20px;
            background: rgba(59, 57, 94, 0.95);
            overflow-y: auto;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            border-right: 2px solid rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }

        .level-badge {
            background: linear-gradient(45deg, #f7d794, #e15f41);
            color: #2c2c54;
            padding: 5px 15px;
            border-radius: 20px;
            text-align: center;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
            animation: glow 2s ease-in-out infinite;
        }

        @keyframes glow {
            0% { box-shadow: 0 0 5px rgba(247, 215, 148, 0.5); }
            50% { box-shadow: 0 0 20px rgba(247, 215, 148, 0.8); }
            100% { box-shadow: 0 0 5px rgba(247, 215, 148, 0.5); }
        }

        .game-field {
            flex-grow: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            position: relative;
        }

        .field {
            width: 80%;
            height: 80%;
            background: linear-gradient(135deg, #424242, #303030);
            border: 2px solid #616161;
            position: relative;
            border-radius: 15px;
            box-shadow: 
                0 0 30px rgba(48, 48, 48, 0.3),
                inset 0 0 100px rgba(255, 255, 255, 0.05);
            overflow: hidden;
            animation: fieldPulse 3s ease-in-out infinite;
        }

        @keyframes fieldPulse {
            0% { 
                box-shadow: 0 0 30px rgba(48, 48, 48, 0.3), 
                           inset 0 0 100px rgba(255, 255, 255, 0.05);
                transform: scale(1);
            }
            50% { 
                box-shadow: 0 0 50px rgba(48, 48, 48, 0.5), 
                           inset 0 0 120px rgba(255, 255, 255, 0.08);
                transform: scale(1.02);
            }
            100% { 
                box-shadow: 0 0 30px rgba(48, 48, 48, 0.3), 
                           inset 0 0 100px rgba(255, 255, 255, 0.05);
                transform: scale(1);
            }
        }

        .field::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                repeating-linear-gradient(
                    45deg,
                    transparent,
                    transparent 10px,
                    rgba(255, 255, 255, 0.05) 10px,
                    rgba(255, 255, 255, 0.05) 20px
                );
        }

        .field::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(
                circle at center,
                transparent,
                rgba(255, 255, 255, 0.2),
                transparent
            );
            transform: rotate(45deg);
            animation: waveEffect 8s linear infinite;
        }

        @keyframes waveEffect {
            0% { transform: rotate(45deg) translate(-50%, -50%); }
            100% { transform: rotate(45deg) translate(50%, 50%); }
        }

        #ball {
            width: 50px;
            height: 50px;
            position: absolute;
            top: 75%;
            left: 15%;
            filter: drop-shadow(0 0 10px rgba(231, 95, 65, 0.6));
            animation: ballBounce 1s ease-in-out infinite;
            transition: all 0.3s ease;
        }

        #target {
            width: 60px;
            height: 60px;
            position: absolute;
            top: 25%;
            right: 15%;
            filter: drop-shadow(0 0 10px rgba(46, 204, 113, 0.6));
            animation: targetPulse 2s ease-in-out infinite;
            transition: all 0.3s ease;
        }

        @keyframes targetPulse {
            0% { transform: scale(1); filter: drop-shadow(0 0 10px rgba(46, 204, 113, 0.6)); }
            50% { transform: scale(1.1); filter: drop-shadow(0 0 20px rgba(46, 204, 113, 0.8)); }
            100% { transform: scale(1); filter: drop-shadow(0 0 10px rgba(46, 204, 113, 0.6)); }
        }

        @keyframes ballBounce {
            0% { transform: scale(1); filter: drop-shadow(0 0 10px rgba(231, 95, 65, 0.6)); }
            50% { transform: scale(1.1); filter: drop-shadow(0 0 20px rgba(231, 95, 65, 0.8)); }
            100% { transform: scale(1); filter: drop-shadow(0 0 10px rgba(231, 95, 65, 0.6)); }
        }

        .css-editor {
            background: rgba(30, 30, 30, 0.95);
            border-radius: 10px;
            padding: 15px;
            margin-top: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .editor-header {
            display: flex;
            gap: 8px;
            margin-bottom: 10px;
        }

        .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }

        .dot-red { background-color: #ff5f56; }
        .dot-yellow { background-color: #ffbd2e; }
        .dot-green { background-color: #27c93f; }

        .css-editor pre {
            color: #abb2bf;
            margin: 5px 0;
            font-family: monospace;
        }

        .css-editor textarea {
            width: 100%;
            min-height: 100px;
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid #3c3c3c;
            border-radius: 5px;
            color: #fff;
            font-family: monospace;
            padding: 10px;
            resize: vertical;
        }

        .css-editor textarea:focus {
            outline: none;
            border-color: #528bff;
        }

        #check-answer {
            background: linear-gradient(45deg, #528bff, #8a2be2);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            margin-top: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        #check-answer:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(82, 139, 255, 0.3);
        }

        #result {
            margin-top: 10px;
            padding: 10px;
            border-radius: 5px;
            font-family: monospace;
        }

        #result.success {
            background: rgba(39, 201, 63, 0.1);
            color: #27c93f;
            border: 1px solid #27c93f;
        }

        #result.error {
            background: rgba(255, 95, 86, 0.1);
            color: #ff5f56;
            border: 1px solid #ff5f56;
        }

        .health-bar {
            width: 100%;
            height: 20px;
            background: rgba(255, 95, 86, 0.2);
            border-radius: 10px;
            margin: 10px 0;
            position: relative;
            overflow: hidden;
        }

        .health {
            height: 100%;
            background: linear-gradient(90deg, #ff5f56, #ff8157);
            border-radius: 10px;
            transition: width 0.3s ease;
        }

        .health-text {
            position: absolute;
            width: 100%;
            text-align: center;
            line-height: 20px;
            color: white;
            mix-blend-mode: difference;
        }
    </style>
</head>
<body>
    <div class="game-container">
        <div class="game-info">
            <div class="level-badge">LEVEL 7</div>
            <h2>Advanced Transform Challenge</h2>
            <p>Heroes' health:</p>
            <div class="health-bar">
                <div class="health-text"><?php echo $_SESSION['health']; ?>%</div>
                <div class="health" style="width: <?php echo $_SESSION['health']; ?>%;"></div>
            </div>
            <p>
                ด่านพิเศษ! ลูกบอลต้องการไปหากล่อง! 
                ใช้ทักษะ transform ขั้นสูงเพื่อพาลูกบอลไปหากล่องให้ได้!
            </p>
            <div class="css-editor">
                <div class="editor-header">
                    <div class="dot dot-red"></div>
                    <div class="dot dot-yellow"></div>
                    <div class="dot dot-green"></div>
                </div>
                <pre>#ball {</pre>
                <textarea id="css-input" placeholder="ใส่คำสั่ง CSS ตรงนี้..."></textarea>
                <pre>}</pre>
                <button id="check-answer">ตรวจคำตอบ</button>
                <div id="result"></div>
            </div>
        </div>
        <div class="game-field">
            <div class="field">
                <img src="ball.png" alt="Ball" id="ball">
                <img src="box.png" alt="Box" id="target">
            </div>
        </div>
    </div>
    <script>
        function applyCSS(cssInput) {
            const ball = document.getElementById('ball');
            const result = document.getElementById('result');
            const healthBar = document.querySelector('.health');
            const healthText = document.querySelector('.health-text');
            
            try {
                // ลบ style เดิมก่อนใส่ style ใหม่
                ball.removeAttribute('style');
                
                // ใส่ style ใหม่
                ball.style.cssText = cssInput;

                // ตรวจสอบตำแหน่งของลูกบอลและกล่อง
                const ballRect = ball.getBoundingClientRect();
                const targetRect = document.getElementById('target').getBoundingClientRect();
                
                // เช็คว่าลูกบอลซ้อนทับกับเป้าหมายหรือไม่
                const overlap = !(ballRect.right < targetRect.left || 
                                ballRect.left > targetRect.right || 
                                ballRect.bottom < targetRect.top || 
                                ballRect.top > targetRect.bottom);

                if (overlap) {
                    result.textContent = 'ยินดีด้วย! คุณผ่านด่านที่ 7 แล้ว!';
                    result.className = 'success';
                    ball.classList.add('bounce');
                    setTimeout(() => {
                        ball.classList.remove('bounce');
                        window.location.href = 'select_level.php';
                    }, 1000);
                } else {
                    // ลด Health 5% เมื่อตอบผิด
                    fetch('update_health.php?decrease=5')
                        .then(response => response.json())
                        .then(data => {
                            const newHealth = data.health;
                            
                            // อัพเดท health bar แบบ realtime
                            healthBar.style.width = newHealth + '%';
                            healthText.textContent = newHealth + '%';
                            
                            // เปลี่ยนสีตามระดับ health
                            if (newHealth <= 20) {
                                healthBar.style.background = 'linear-gradient(90deg, #ff0000, #cc0000)';
                            } else if (newHealth <= 50) {
                                healthBar.style.background = 'linear-gradient(90deg, #ffa500, #ff8c00)';
                            } else {
                                healthBar.style.background = 'linear-gradient(90deg, #ff6b6b, #ee5253)';
                            }
                            
                            // ถ้า health หมด กลับไปด่าน 1
                            if (newHealth <= 0) {
                                alert('เลือดหมด! เริ่มเกมใหม่ที่ด่าน 1');
                                window.location.href = 'game1.php';
                            } else {
                                result.textContent = 'ยังไม่ถูกต้อง ลองใหม่อีกครั้ง (-5% HP)';
                                result.className = 'error';
                            }
                        });
                }
            } catch (error) {
                result.textContent = 'คำสั่ง CSS ไม่ถูกต้อง: ' + error.message;
                result.className = 'error';
            }
        }

        // เมื่อพิมพ์ CSS
        document.getElementById('css-input').addEventListener('input', function() {
            applyCSS(this.value);
        });

        // เมื่อกดปุ่มตรวจคำตอบ
        document.getElementById('check-answer').addEventListener('click', function() {
            const cssInput = document.getElementById('css-input').value;
            applyCSS(cssInput);
        });
    </script>
</body>
</html>
