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
    <title>CSS Adventure Game - Level 9</title>
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
            background: linear-gradient(45deg, #8e44ad, #2980b9);
            color: #fff;
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
            0% { box-shadow: 0 0 5px rgba(142, 68, 173, 0.5); }
            50% { box-shadow: 0 0 20px rgba(142, 68, 173, 0.8); }
            100% { box-shadow: 0 0 5px rgba(142, 68, 173, 0.5); }
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
            background: linear-gradient(135deg, #34495e, #2c3e50);
            border: 2px solid #3498db;
            position: relative;
            border-radius: 15px;
            box-shadow: 
                0 0 30px rgba(41, 128, 185, 0.3),
                inset 0 0 100px rgba(255, 255, 255, 0.05);
            overflow: hidden;
            animation: fieldPulse 3s ease-in-out infinite;
        }

        @keyframes fieldPulse {
            0% { 
                box-shadow: 0 0 30px rgba(41, 128, 185, 0.3), 
                           inset 0 0 100px rgba(255, 255, 255, 0.05);
                transform: scale(1);
            }
            50% { 
                box-shadow: 0 0 50px rgba(41, 128, 185, 0.5), 
                           inset 0 0 120px rgba(255, 255, 255, 0.08);
                transform: scale(1.02);
            }
            100% { 
                box-shadow: 0 0 30px rgba(41, 128, 185, 0.3), 
                           inset 0 0 100px rgba(255, 255, 255, 0.05);
                transform: scale(1);
            }
        }

        .obstacle {
            position: absolute;
            background: rgba(231, 76, 60, 0.7);
            border: 2px solid #c0392b;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(231, 76, 60, 0.5);
            animation: obstaclePulse 2s ease-in-out infinite;
        }

        #obstacle1 {
            width: 70px;
            height: 70px;
            top: 20%;
            left: 30%;
            animation: obstacle1Move 3s linear infinite;
        }

        #obstacle2 {
            width: 50px;
            height: 100px;
            top: 40%;
            right: 25%;
            animation: obstacle2Move 4s linear infinite;
        }

        #obstacle3 {
            width: 90px;
            height: 50px;
            bottom: 30%;
            left: 45%;
            animation: obstacle3Move 5s ease-in-out infinite;
        }

        #obstacle4 {
            width: 60px;
            height: 60px;
            top: 60%;
            left: 20%;
            animation: obstacle4Move 4.5s ease-in-out infinite;
        }

        #obstacle5 {
            width: 80px;
            height: 40px;
            bottom: 40%;
            right: 35%;
            animation: obstacle5Move 3.5s linear infinite;
        }

        @keyframes obstacle1Move {
            0% { transform: translate(0, 0) rotate(0deg); }
            50% { transform: translate(120px, 60px) rotate(180deg); }
            100% { transform: translate(0, 0) rotate(360deg); }
        }

        @keyframes obstacle2Move {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(-100px, 100px) scale(1.3); }
            100% { transform: translate(0, 0) scale(1); }
        }

        @keyframes obstacle3Move {
            0% { transform: translate(0, 0) skew(0deg); }
            50% { transform: translate(80px, -60px) skew(15deg); }
            100% { transform: translate(0, 0) skew(0deg); }
        }

        @keyframes obstacle4Move {
            0% { transform: translate(0, 0) rotate(0deg) scale(1); }
            50% { transform: translate(70px, -40px) rotate(180deg) scale(1.2); }
            100% { transform: translate(0, 0) rotate(360deg) scale(1); }
        }

        @keyframes obstacle5Move {
            0% { transform: translate(0, 0) skew(-10deg); }
            50% { transform: translate(-60px, 30px) skew(10deg); }
            100% { transform: translate(0, 0) skew(-10deg); }
        }

        #ball {
            width: 50px;
            height: 50px;
            position: absolute;
            top: 85%;
            left: 5%;
            filter: drop-shadow(0 0 10px rgba(155, 89, 182, 0.6));
            animation: ballPulse 1.5s ease-in-out infinite;
            transition: all 0.3s ease;
        }

        #target {
            width: 60px;
            height: 60px;
            position: absolute;
            top: 5%;
            right: 5%;
            filter: drop-shadow(0 0 10px rgba(241, 196, 15, 0.6));
            animation: targetFloat 3s ease-in-out infinite;
            transition: all 0.3s ease;
        }

        @keyframes targetFloat {
            0% { transform: translate(0, 0) rotate(0deg) scale(1); }
            50% { transform: translate(-20px, -20px) rotate(180deg) scale(1.1); }
            100% { transform: translate(0, 0) rotate(360deg) scale(1); }
        }

        @keyframes ballPulse {
            0% { transform: scale(1); filter: drop-shadow(0 0 10px rgba(155, 89, 182, 0.6)); }
            50% { transform: scale(1.1); filter: drop-shadow(0 0 20px rgba(155, 89, 182, 0.8)); }
            100% { transform: scale(1); filter: drop-shadow(0 0 10px rgba(155, 89, 182, 0.6)); }
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

        .editor-content {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 5px;
            padding: 10px;
        }

        textarea {
            width: 100%;
            min-height: 150px;
            background: transparent;
            border: none;
            color: #fff;
            font-family: monospace;
            resize: vertical;
            outline: none;
        }

        button {
            background: linear-gradient(45deg, #8e44ad, #2980b9);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(142, 68, 173, 0.3);
        }

        .health-container {
            margin-top: 20px;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            padding: 10px;
        }

        .health-bar {
            height: 20px;
            background: rgba(231, 76, 60, 0.3);
            border-radius: 10px;
            overflow: hidden;
        }

        .health {
            height: 100%;
            background: linear-gradient(90deg, #8e44ad, #2980b9);
            width: <?php echo $_SESSION['health']; ?>%;
            transition: width 0.3s ease;
        }
        .health-text {
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="game-container">
        <div class="game-info">
            <div class="level-badge">LEVEL 9</div>
            <h2>Expert Transform Challenge</h2>
            <p>Navigate through five moving obstacles in this ultimate challenge!</p>
            <div class="health-container">
                <p class="health-text">Health: <?php echo $_SESSION['health']; ?>%</p>
                <div class="health-bar">
                    <div class="health"></div>
                </div>
            </div>
            <div class="css-editor">
                <div class="editor-header">
                    <div class="dot dot-red"></div>
                    <div class="dot dot-yellow"></div>
                    <div class="dot dot-green"></div>
                </div>
                <div class="editor-content">
                    <textarea id="cssInput" placeholder="Enter your CSS transform here...">#ball {
    transform: /* Your transform here */
}</textarea>
                    <button onclick="applyCSS(document.getElementById('cssInput').value)">Apply CSS</button>
                </div>
            </div>
            <div id="result"></div>
        </div>
        <div class="game-field">
            <div class="field">
                <img src="ball.png" id="ball" alt="Ball">
                <img src="box.png" id="target" alt="Target">
                <div class="obstacle" id="obstacle1"></div>
                <div class="obstacle" id="obstacle2"></div>
                <div class="obstacle" id="obstacle3"></div>
                <div class="obstacle" id="obstacle4"></div>
                <div class="obstacle" id="obstacle5"></div>
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
                const targetRect = document.getElementById('target').getBoundingClientRect();
                
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
                    result.innerHTML = '<p style="color: #2ecc71;">ยินดีด้วย! ผ่านด่านที่ 9 แล้ว!</p>';
                    setTimeout(() => {
                        window.location.href = 'game10.php';
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
