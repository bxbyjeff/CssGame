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
    <title>CSS Adventure Game</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #2b2a4d;
            color: #fff;
        }

        .game-container {
            display: flex;
            height: 100vh;
        }

        .game-info {
            width: 30%;
            padding: 20px;
            background-color: #3b395e;
            overflow-y: auto;
        }

        .game-info h2 {
            text-align: center;
            color: #9f7aea;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .health-bar {
            width: 100%;
            height: 20px;
            background-color: #444;
            border-radius: 5px;
            margin: 10px 0;
            overflow: hidden;
            position: relative;
        }

        .health {
            height: 100%;
            background-color: #e53e3e;
            transition: width 0.3s ease;
        }

        .css-editor {
            margin-top: 20px;
            background-color: #2d3748;
            padding: 15px;
            border-radius: 8px;
        }

        textarea {
            width: 100%;
            height: 100px;
            background-color: #1a202c;
            color: #fff;
            border: 1px solid #4a5568;
            padding: 10px;
            font-family: monospace;
            font-size: 16px;
            border-radius: 5px;
            margin: 10px 0;
        }

        button {
            background-color: #6f42c1;
            color: #fff;
            padding: 12px;
            border: none;
            margin-top: 10px;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #553c9a;
        }

        .game-field {
            width: 70%;
            background-color: #4a4a72;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .field {
            width: 80%;
            height: 80%;
            background-color: #6e7b52;
            border: 2px solid #2d3748;
            position: relative;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .knight {
            position: absolute;
            top: 10%;
            left: 10%;
            width: 50px;
            height: auto;
            transition: all 0.3s ease;
        }

        .apple {
            position: absolute;
            top: 70%;
            left: 80%;
            width: 40px;
            height: auto;
        }

        #result {
            margin-top: 15px;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }

        .success {
            background-color: #48bb78;
            color: white;
        }

        .error {
            background-color: #e53e3e;
            color: white;
        }
    </style>
</head>
<body>
    <div class="game-container">
        <div class="game-info">
            <h2>Level 1</h2>
            <p>Heroes' health:</p>
            <div class="health-bar">
                <div class="health" style="width: 50%;"></div>
            </div>
            <p>50/100 ❤️</p>
            <p>
                สวัสดี ฮีโร่! เราต้องช่วยต้นไม้ให้ไปถึงแหล่งน้ำ! พร้อมรึยังสำหรับการผจญภัย?
            </p>
            <p>
                ดูเหมือนว่าต้นไม้จะเหี่ยวเฉาลงเรื่อยๆ เราต้องรีบพาไปหาน้ำ! ใช้คำสั่ง CSS เพื่อควบคุมตำแหน่งของต้นไม้กันเถอะ
            </p>
            <div class="css-editor">
                <pre>#tree {</pre>
                <textarea id="css-input" placeholder="ใส่คำสั่ง CSS ตรงนี้..."></textarea>
                <pre>}</pre>
                <button id="check-answer">ตรวจคำตอบ</button>
                <div id="result"></div>
            </div>
        </div>

        <div class="game-field">
            <div id="field" class="field">
                <img src="tree.png" alt="Tree" class="knight" id="tree">
                <img src="water.png" alt="Water" class="apple">
            </div>
        </div>
    </div>

    <script>
        document.getElementById('css-input').addEventListener('input', function() {
            const cssInput = this.value;
            const tree = document.getElementById('tree');

            try {
                tree.style.cssText = cssInput;
            } catch (error) {
                console.error('Invalid CSS:', error);
            }
        });

        document.getElementById('check-answer').addEventListener('click', function() {
            const tree = document.getElementById('tree');
            const water = document.querySelector('.apple');
            const result = document.getElementById('result');

            const treeRect = tree.getBoundingClientRect();
            const waterRect = water.getBoundingClientRect();

            if (
                treeRect.right >= waterRect.left &&
                treeRect.left <= waterRect.right &&
                treeRect.bottom >= waterRect.top &&
                treeRect.top <= waterRect.bottom
            ) {
                result.textContent = '🎉 เยี่ยมมาก! ต้นไม้ถึงน้ำแล้ว!';
                result.className = 'success';
                setTimeout(() => {
                    window.location.href = 'game2.php';
                }, 1500);
            } else {
                result.textContent = '❌ ยังไม่ถึง! ลองปรับคำสั่ง CSS อีกครั้ง';
                result.className = 'error';
            }
        });
    </script>
</body>
</html>
