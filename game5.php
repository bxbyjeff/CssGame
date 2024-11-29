<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSS Adventure Game - Level 5</title>
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
            background: linear-gradient(45deg, #28a745 0%, #20c997 100%);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid #000;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.3);
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

        .tree {
            width: 50px;  
            height: 50px;  
            position: absolute;
            transition: all 0.3s ease;
            filter: drop-shadow(0 0 10px rgba(29, 74, 44, 0.5));
        }

        .tree.wiggle {
            animation: treeWiggle 0.5s ease-in-out, treePulse 2s ease-in-out infinite;
        }

        @keyframes treePulse {
            0% { filter: drop-shadow(0 0 10px rgba(29, 74, 44, 0.5)); }
            50% { filter: drop-shadow(0 0 20px rgba(46, 204, 113, 0.8)); }
            100% { filter: drop-shadow(0 0 10px rgba(29, 74, 44, 0.5)); }
        }

        @keyframes treeWiggle {
            0% { transform: rotate(0deg) scale(1); filter: brightness(1); }
            25% { transform: rotate(-5deg) scale(1.1); filter: brightness(1.2); }
            75% { transform: rotate(5deg) scale(1.1); filter: brightness(1.2); }
            100% { transform: rotate(0deg) scale(1); filter: brightness(1); }
        }

        .knight {
            position: absolute;
            top: 10%;
            left: 10%;
            width: 50px;
            height: auto;
            transition: all 0.5s ease;
            filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.5));
            z-index: 2;
            animation: treeMove 2s infinite;
        }

        .knight.wiggle {
            animation: treeWiggle 0.5s ease;
        }

        .apple {
            position: absolute;
            top: 90%;
            left: 90%;
            width: 40px;
            height: auto;
            filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.3));
        }

        .obstacle {
            position: absolute;
            background: linear-gradient(90deg, #2d3748, #1a202c);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .obstacle-1 {
            width: 60%;
            height: 20px;
            left: 20%;
            top: 30%;
            transform: rotate(45deg);
        }

        .obstacle-2 {
            width: 60%;
            height: 20px;
            left: 20%;
            top: 60%;
            transform: rotate(-45deg);
        }

        .obstacle-3 {
            width: 20px;
            height: 60%;
            left: 50%;
            top: 20%;
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
    </style>
</head>
<body>
    <div class="game-container">
        <div class="game-info">
            <div class="level-badge">LEVEL 5</div>
            <h2>Transform Expert</h2>
            <p>Heroes' health:</p>
            <div class="health-bar">
                <div class="health-text"><?php echo $_SESSION['health']; ?>%</div>
                <div class="health" style="width: <?php echo $_SESSION['health']; ?>%;"></div>
            </div>
            <p>
                ด่านสุดท้าย! ต้นไม้กำลังจะตายแล้ว! 
                เราต้องใช้ทั้ง transform และ position 
                เพื่อพาต้นไม้ผ่านกำแพงที่ซับซ้อนไปหาน้ำให้ได้!
            </p>
            <div class="css-editor">
                <div class="editor-header">
                    <div class="dot dot-red"></div>
                    <div class="dot dot-yellow"></div>
                    <div class="dot dot-green"></div>
                </div>
                <pre>#tree {</pre>
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
                <img src="tree.png" alt="Tree" class="tree" id="tree">
                <img src="water.png" alt="Water" class="apple">
            </div>
        </div>
    </div>

    <script>
        document.getElementById('css-input').addEventListener('input', function() {
            const cssInput = this.value;
            const tree = document.getElementById('tree');

            // แยกคำสั่ง CSS เป็นบรรทัด
            const cssLines = cssInput.split(';');
            
            // ประมวลผลแต่ละบรรทัด
            cssLines.forEach(line => {
                const [property, value] = line.split(':').map(str => str.trim());
                if (property && value) {
                    try {
                        // กำหนดค่า style ตามที่ผู้เล่นใส่
                        tree.style[property] = value;
                    } catch (error) {
                        console.error('Invalid CSS:', error);
                    }
                }
            });
        });

        document.getElementById('check-answer').addEventListener('click', function() {
            const tree = document.getElementById('tree');
            const water = document.querySelector('.apple');
            const result = document.getElementById('result');

            const treeRect = tree.getBoundingClientRect();
            const waterRect = water.getBoundingClientRect();

            const distance = Math.sqrt(
                Math.pow(treeRect.left - waterRect.left, 2) +
                Math.pow(treeRect.top - waterRect.top, 2)
            );

            if (distance < 50) {
                result.textContent = 'ยินดีด้วย! คุณผ่านทุกด่านแล้ว! ';
                result.className = 'success';
                tree.classList.add('wiggle');
                setTimeout(() => {
                    tree.classList.remove('wiggle');
                    // ไปด่านที่ 6
                    alert('ยินดีด้วย! คุณช่วยต้นไม้สำเร็จแล้ว! ไปด่านต่อไปกัน!');
                    window.location.href = 'game6.php';
                }, 1000);
            } else {
                result.textContent = 'ยังไม่ถูกต้อง ลองอีกครั้ง! ';
                result.className = 'error';
            }
        });
    </script>
</body>
</html>
