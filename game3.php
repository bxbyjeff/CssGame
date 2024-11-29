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
    <title>CSS Adventure Game - Level 3</title>
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

        @keyframes treeWiggle {
            0% { transform: rotate(0deg) scale(1); }
            25% { transform: rotate(-5deg) scale(1.1); }
            75% { transform: rotate(5deg) scale(1.1); }
            100% { transform: rotate(0deg) scale(1); }
        }

        @keyframes treeMove {
            0% { transform: translate(0, 0); }
            50% { transform: translate(3px, 3px); }
            100% { transform: translate(0, 0); }
        }

        .knight {
            position: absolute;
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
            width: 40px;
            height: auto;
            bottom: 20px;
            right: 20px;
            filter: drop-shadow(0 0 8px rgba(255, 255, 255, 0.5));
            z-index: 2;
        }

        .flex-container {
            width: 100%;
            height: 100%;
            position: relative;
        }

        /* ตำแหน่งเริ่มต้น */
        .flex-container .knight {
            top: 20px;
            left: 20px;
        }

        /* ตำแหน่งเมื่อใช้ flex-end */
        .flex-container[style*="display: flex"][style*="justify-content: flex-end"][style*="align-items: flex-end"] .knight {
            top: auto;
            left: auto;
            bottom: 20px;
            right: 20px;
        }

        /* เพิ่มเอฟเฟกต์พื้นหญ้า */
        .grass {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 30px;
            background: 
                linear-gradient(to bottom, transparent 0%, rgba(0,0,0,0.05) 100%),
                linear-gradient(to right, #c5e1a5, #aed581);
            filter: brightness(1.1);
        }

        /* เพิ่มเอฟเฟกต์เมฆ */
        .cloud {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50px;
            animation: float 20s infinite linear;
        }

        .cloud:nth-child(1) {
            width: 100px;
            height: 40px;
            top: 20%;
            left: -100px;
        }

        .cloud:nth-child(2) {
            width: 80px;
            height: 30px;
            top: 40%;
            left: -80px;
            animation-delay: -5s;
        }

        @keyframes float {
            from { transform: translateX(-100px); }
            to { transform: translateX(calc(100% + 100px)); }
        }

        #result {
            margin-top: 15px;
            padding: 12px;
            border-radius: 8px;
            text-align: center;
            font-weight: 600;
            opacity: 0;
            transform: translateY(-10px);
            transition: all 0.3s ease;
        }

        #result.show {
            opacity: 1;
            transform: translateY(0);
        }

        .success {
            background: linear-gradient(45deg, #00b09b, #96c93d);
            color: white;
        }

        .error {
            background: linear-gradient(45deg, #ff512f, #dd2476);
            color: white;
        }

        .hint {
            margin-top: 15px;
            padding: 10px;
            background: rgba(66, 153, 225, 0.1);
            border-left: 4px solid #4299e1;
            border-radius: 4px;
            font-size: 14px;
            color: #a0aec0;
        }

        .challenge-text {
            background: rgba(0, 0, 0, 0.2);
            padding: 15px;
            border-radius: 10px;
            margin: 15px 0;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body>
    <div class="game-container">
        <div class="game-info">
            <div class="level-badge">LEVEL 3</div>
            <h2>Flexbox Master</h2>
            <p>Heroes' health:</p>
            <div class="health-bar">
                <div class="health-text"><?php echo $_SESSION['health']; ?>%</div>
                <div class="health" style="width: <?php echo $_SESSION['health']; ?>%;"></div>
            </div>
            
            <div class="challenge-text">
                <p>
                    ยินดีด้วย! คุณมาถึงด่านสุดท้ายแล้ว! 
                    ในด่านนี้เราต้องใช้พลังของ Flexbox เพื่อช่วยต้นไม้ไปหาน้ำ
                </p>
                <p class="mt-2">
                    ใช้ความรู้เรื่อง display: flex และ justify-content, align-items 
                    เพื่อจัดวางต้นไม้ให้ไปถึงน้ำกันเถอะ!
                </p>
            </div>

            <div class="css-editor">
                <div class="editor-header">
                    <div class="dot dot-red"></div>
                    <div class="dot dot-yellow"></div>
                    <div class="dot dot-green"></div>
                </div>
                <pre>.flex-container {</pre>
                <textarea id="css-input" placeholder="ใส่ CSS ของคุณที่นี่..."></textarea>
                <pre>}</pre>
                <button id="check-answer">ตรวจสอบคำตอบ</button>
                <div id="result"></div>
                <div class="hint">
                    &#x1F4A1; Hint: ลองใช้ display: flex ร่วมกับ justify-content และ align-items
                </div>
            </div>
        </div>

        <div class="game-field">
            <div class="field">
                <div class="cloud"></div>
                <div class="cloud"></div>
                <div class="grass"></div>
                <div class="flex-container" id="flex-container">
                    <img src="tree.png" alt="Tree" class="knight" id="tree">
                    <img src="water.png" alt="Water" class="apple">
                </div>
            </div>
        </div>
    </div>

    <script>
        const cssInput = document.getElementById('css-input');
        const flexContainer = document.getElementById('flex-container');
        const tree = document.getElementById('tree');
        const result = document.getElementById('result');

        cssInput.addEventListener('input', function() {
            const cssText = this.value.trim();
            try {
                flexContainer.style.cssText = cssText;
                tree.classList.add('wiggle');
                setTimeout(() => {
                    tree.classList.remove('wiggle');
                }, 500);

                // ตรวจสอบและปรับตำแหน่งต้นไม้
                const styles = window.getComputedStyle(flexContainer);
                const isCorrectFlex = 
                    styles.display === 'flex' && 
                    styles.justifyContent === 'flex-end' && 
                    styles.alignItems === 'flex-end';

                if (isCorrectFlex) {
                    tree.style.transition = 'all 0.5s ease';
                    tree.style.top = 'auto';
                    tree.style.left = 'auto';
                    tree.style.bottom = '20px';
                    tree.style.right = '20px';
                } else {
                    tree.style.transition = 'all 0.5s ease';
                    tree.style.top = '20px';
                    tree.style.left = '20px';
                    tree.style.bottom = 'auto';
                    tree.style.right = 'auto';
                }
            } catch (error) {
                console.error('Invalid CSS:', error);
            }
        });

        document.getElementById('check-answer').addEventListener('click', function() {
            const styles = window.getComputedStyle(flexContainer);
            
            const isCorrectDisplay = styles.display === 'flex';
            const isCorrectJustify = styles.justifyContent === 'flex-end';
            const isCorrectAlign = styles.alignItems === 'flex-end';

            const tree = document.getElementById('tree');
            const water = document.querySelector('.apple');
            const treeRect = tree.getBoundingClientRect();
            const waterRect = water.getBoundingClientRect();

            if (isCorrectDisplay && isCorrectJustify && isCorrectAlign) {
                result.textContent = '&#x1F389; เยี่ยมมาก! คุณเข้าใจการใช้ Flexbox แล้ว!';
                result.className = 'success show';
                setTimeout(() => {
                    window.location.href = 'game4.php';
                }, 1500);
            } else {
                let message = '&#x1F6AB; ยังไม่ถูกต้อง! ตรวจสอบว่า:';
                if (!isCorrectDisplay) message += '\n- ต้องใช้ display: flex';
                if (!isCorrectJustify) message += '\n- ต้องใช้ justify-content: flex-end';
                if (!isCorrectAlign) message += '\n- ต้องใช้ align-items: flex-end';
                result.textContent = message;
                result.className = 'error show';
            }
        });
    </script>
</body>
</html>
