<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// ตรวจสอบว่ามี health หรือไม่
if (!isset($_SESSION['health'])) {
    $_SESSION['health'] = 100;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSS Adventure Game - Level 6</title>
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

        .game-info h2 {
            text-align: center;
            color: #f7d794;
            font-size: 28px;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .level-badge {
            background: linear-gradient(45deg, #ff6b6b, #ff8e8e);
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            display: inline-block;
            margin-bottom: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .health-bar {
            width: 100%;
            height: 20px;
            background-color: rgba(68, 68, 68, 0.5);
            border-radius: 10px;
            margin: 10px 0;
            overflow: hidden;
            position: relative;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .health {
            height: 100%;
            background: linear-gradient(90deg, #ff6b6b, #ee5253);
            transition: width 0.5s ease-in-out;
            box-shadow: 0 0 10px rgba(238, 82, 83, 0.5);
            position: relative;
        }

        .health-text {
            position: absolute;
            width: 100%;
            text-align: center;
            color: white;
            font-weight: bold;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
            z-index: 1;
            line-height: 20px;
            font-size: 12px;
        }

        .css-editor {
            margin-top: 20px;
            background: rgba(45, 55, 72, 0.9);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        .editor-header {
            background: rgba(26, 32, 44, 0.9);
            padding: 10px;
            border-radius: 8px 8px 0 0;
            display: flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 10px;
        }

        .dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            margin-right: 5px;
        }

        .dot-red { background-color: #ff5f56; }
        .dot-yellow { background-color: #ffbd2e; }
        .dot-green { background-color: #27c93f; }

        textarea {
            width: 100%;
            height: 120px;
            background-color: rgba(26, 32, 44, 0.95);
            color: #a0aec0;
            border: 1px solid rgba(74, 85, 104, 0.3);
            padding: 15px;
            font-family: 'Monaco', 'Menlo', monospace;
            font-size: 14px;
            border-radius: 8px;
            margin: 10px 0;
            resize: none;
            transition: all 0.3s ease;
        }

        textarea:focus {
            outline: none;
            border-color: #4299e1;
            box-shadow: 0 0 0 2px rgba(66, 153, 225, 0.2);
        }

        button {
            background: linear-gradient(45deg, #6b46c1, #805ad5);
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            width: 100%;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 4px 6px rgba(107, 70, 193, 0.2);
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 8px rgba(107, 70, 193, 0.3);
        }

        .game-field {
            width: 70%;
            background: linear-gradient(45deg, #4a90e2 0%, #357abd 100%);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid #2c5282;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.3);
            overflow: hidden;
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
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, rgba(255,255,255,0) 70%);
            animation: rotate 10s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .field::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 300%;
            height: 100%;
            background: linear-gradient(90deg, 
                rgba(255,255,255,0) 0%,
                rgba(255,255,255,0.08) 50%,
                rgba(255,255,255,0) 100%);
            transform: skewX(-45deg);
            animation: shine 6s ease-in-out infinite;
        }

        @keyframes shine {
            0% { transform: translateX(-100%) skewX(-45deg); }
            50% { transform: translateX(100%) skewX(-45deg); }
            100% { transform: translateX(-100%) skewX(-45deg); }
        }

        .obstacle {
            position: absolute;
            border-radius: 50%;
            background: radial-gradient(circle at 30% 30%, #4a5568, #1a202c);
            box-shadow: 
                0 0 20px rgba(0, 0, 0, 0.4),
                inset 0 0 30px rgba(255, 255, 255, 0.1);
            animation: glow 2s infinite alternate;
        }

        @keyframes glow {
            0% {
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.4), inset 0 0 30px rgba(255, 255, 255, 0.1);
            }
            100% {
                box-shadow: 0 0 30px rgba(147, 51, 234, 0.5), inset 0 0 50px rgba(147, 51, 234, 0.3);
            }
        }

        .obstacle-1 {
            width: 100px;
            height: 100px;
            left: 30%;
            top: 20%;
            clip-path: polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%);
            animation: rotate1 4s linear infinite;
        }

        .obstacle-2 {
            width: 120px;
            height: 120px;
            right: 30%;
            top: 60%;
            clip-path: polygon(50% 0%, 100% 38%, 82% 100%, 18% 100%, 0% 38%);
            animation: rotate2 5s linear infinite;
        }

        .obstacle-3 {
            width: 90px;
            height: 90px;
            left: 50%;
            top: 40%;
            transform: translateX(-50%);
            clip-path: polygon(30% 0%, 70% 0%, 100% 30%, 100% 70%, 70% 100%, 30% 100%, 0% 70%, 0% 30%);
            animation: rotate3 3s linear infinite;
        }

        @keyframes rotate1 {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        @keyframes rotate2 {
            from { transform: rotate(360deg); }
            to { transform: rotate(0deg); }
        }

        @keyframes rotate3 {
            0% { transform: translateX(-50%) rotate(0deg) scale(1); }
            50% { transform: translateX(-50%) rotate(180deg) scale(1.2); }
            100% { transform: translateX(-50%) rotate(360deg) scale(1); }
        }

        .obstacle::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: repeating-linear-gradient(
                45deg,
                rgba(255, 255, 255, 0.1),
                rgba(255, 255, 255, 0.1) 10px,
                transparent 10px,
                transparent 20px
            );
            border-radius: inherit;
            animation: pattern 20s linear infinite;
        }

        @keyframes pattern {
            from { background-position: 0 0; }
            to { background-position: 100px 100px; }
        }

        @keyframes ballBounce {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        @keyframes ballMove {
            0% { transform: translate(0, 0); }
            50% { transform: translate(3px, 3px); }
            100% { transform: translate(0, 0); }
        }

        .ball {
            position: absolute;
            top: 5%;
            left: 5%;
            width: 50px;
            height: auto;
            transition: all 0.5s ease;
            filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.5));
            z-index: 2;
            animation: ballMove 2s infinite;
        }

        .ball.bounce {
            animation: ballBounce 0.5s ease;
        }

        .box {
            position: absolute;
            top: 85%;
            left: 85%;
            width: 60px;
            height: auto;
            filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.3));
        }

        #result {
            margin-top: 15px;
            padding: 10px;
            border-radius: 8px;
            text-align: center;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .success {
            background: linear-gradient(45deg, #48bb78, #38a169);
            color: white;
            box-shadow: 0 2px 4px rgba(72, 187, 120, 0.3);
        }

        .error {
            background: linear-gradient(45deg, #e53e3e, #c53030);
            color: white;
            box-shadow: 0 2px 4px rgba(229, 62, 62, 0.3);
        }

        .field::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 300%;
            height: 200%;
            background: linear-gradient(
                90deg,
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

        .ball {
            width: 40px;
            height: 40px;
            position: absolute;
            top: 10%;
            left: 10%;
            transition: all 0.3s ease;
            filter: drop-shadow(0 0 10px rgba(255, 255, 255, 0.3));
            animation: floatBall 2s ease-in-out infinite;
        }

        @keyframes floatBall {
            0% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0); }
        }

        .box {
            width: 50px;
            height: 50px;
            position: absolute;
            bottom: 10%;
            right: 10%;
            filter: drop-shadow(0 0 15px rgba(255, 255, 255, 0.4));
            animation: glowBox 2s ease-in-out infinite;
        }

        @keyframes glowBox {
            0% { filter: drop-shadow(0 0 15px rgba(255, 255, 255, 0.4)); }
            50% { filter: drop-shadow(0 0 25px rgba(255, 255, 255, 0.6)); }
            100% { filter: drop-shadow(0 0 15px rgba(255, 255, 255, 0.4)); }
        }
    </style>
</head>
<body>
    <div class="game-container">
        <div class="game-info">
            <div class="level-badge">LEVEL 6</div>
            <h2>Ball & Box Challenge</h2>
            <p>Heroes' health:</p>
            <div class="health-bar">
                <div class="health-text"><?php echo $_SESSION['health']; ?>%</div>
                <div class="health" style="width: <?php echo $_SESSION['health']; ?>%;"></div>
            </div>
            <p>
                ด่านพิเศษ! ลูกบอลต้องการไปหากล่อง! 
                ใช้ทักษะทั้งหมดที่เรียนมาเพื่อพาลูกบอลผ่านกำแพงสามชิ้นไปให้ได้!
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
            <div id="field" class="field">
                <div class="obstacle obstacle-1"></div>
                <div class="obstacle obstacle-2"></div>
                <div class="obstacle obstacle-3"></div>
                <img src="ball.png" alt="Ball" class="ball" id="ball">
                <img src="box.png" alt="Box" class="box">
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
                const match = cssInput.match(/transform\s*:\s*([^;]+)/);
                if (!match) {
                    throw new Error('No transform property found');
                }
                
                ball.style.transform = match[1];
                
                const ballRect = ball.getBoundingClientRect();
                const targetRect = document.querySelector('.box').getBoundingClientRect();
                
                const centerBall = {
                    x: ballRect.left + ballRect.width / 2,
                    y: ballRect.top + ballRect.height / 2
                };
                
                const centerTarget = {
                    x: targetRect.left + targetRect.width / 2,
                    y: targetRect.top + targetRect.height / 2
                };
                
                const distance = Math.sqrt(
                    Math.pow(centerBall.x - centerTarget.x, 2) + 
                    Math.pow(centerBall.y - centerTarget.y, 2)
                );
                
                const obstacles = document.querySelectorAll('.obstacle');
                let collision = false;
                
                obstacles.forEach(obstacle => {
                    const obstacleRect = obstacle.getBoundingClientRect();
                    if (!(ballRect.right < obstacleRect.left || 
                          ballRect.left > obstacleRect.right || 
                          ballRect.bottom < obstacleRect.top || 
                          ballRect.top > obstacleRect.bottom)) {
                        collision = true;
                    }
                });

                if (collision) {
                    result.innerHTML = '<p style="color: #e74c3c;">ชนกำแพง! ลองใหม่อีกครั้ง</p>';
                    fetch('update_health.php?damage=10')
                        .then(response => response.text())
                        .then(health => {
                            healthBar.style.width = health + '%';
                            healthText.textContent = health + '%';
                            if (health <= 0) {
                                alert('เลือดหมด! เริ่มเกมใหม่');
                                window.location.href = 'game1.php';
                            }
                        });
                    return;
                }
                
                if (distance < 50) {
                    result.innerHTML = '<p style="color: #2ecc71;">ยินดีด้วย! ผ่านด่านที่ 6 แล้ว!</p>';
                    setTimeout(() => {
                        window.location.href = 'game7.php';
                    }, 1000);
                } else {
                    result.innerHTML = '<p style="color: #e74c3c;">ยังไม่ถึงเป้าหมาย ลองใหม่อีกครั้ง</p>';
                    fetch('update_health.php?damage=10')
                        .then(response => response.text())
                        .then(health => {
                            healthBar.style.width = health + '%';
                            healthText.textContent = health + '%';
                            if (health <= 0) {
                                alert('เลือดหมด! เริ่มเกมใหม่');
                                window.location.href = 'game1.php';
                            }
                        });
                }
            } catch (error) {
                result.innerHTML = '<p style="color: #e74c3c;">คำสั่ง CSS ไม่ถูกต้อง ลองใหม่อีกครั้ง</p>';
            }
        }

        document.getElementById('check-answer').addEventListener('click', function() {
            const cssInput = document.getElementById('css-input').value;
            applyCSS(cssInput);
        });
    </script>
</body>
</html>
