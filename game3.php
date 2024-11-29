<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
if (!isset($_SESSION['level'])) {
    $_SESSION['level'] = 1;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSS Adventure Game - Level 5</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
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
            background-color: red;
        }

        .css-editor {
            margin-top: 20px;
        }

        textarea {
            width: 100%;
            height: 80px;
            background-color: #272640;
            color: #fff;
            border: 1px solid #444;
            padding: 10px;
            font-family: monospace;
            font-size: 16px;
            border-radius: 5px;
        }

        button {
            background-color: #6f42c1;
            color: #fff;
            padding: 10px;
            border: none;
            margin-top: 10px;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
        }

        button:hover {
            background-color: #563d7c;
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
            border: 2px solid #000;
            position: relative;
        }

        .knight {
            position: absolute;
            width: 50px;
            height: auto;
        }

        .apple {
            position: absolute;
            width: 40px;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="game-container">
        <!-- Game Info Section -->
        <div class="game-info">
            <h2>Level 5</h2>
            <p>Heroes' health:</p>
            <div class="health-bar">
                <div class="health" style="width: 50%;"></div>
            </div>
            <p>50/100 ❤️</p>
            <p>Help Arthur overcome the final challenge!</p>
            <p>
                This level is trickier! You must use CSS to move Arthur through an obstacle course. The final challenge involves rotating and translating elements together to move Arthur towards the water. Good luck!
            </p>

            <div class="css-editor">
                <pre>#field {</pre>
                <textarea id="css-input" placeholder="Type your answer here..."></textarea>
                <pre>}</pre>
                <button id="check-answer">Check Answer</button>
                <p id="result"></p>
            </div>
        </div>

        <!-- Game Field Section -->
        <div class="game-field">
            <div id="field" class="field">
                <!-- Tree and Water -->
                <img src="tree.png" alt="Tree" class="knight" id="knight">
                <img src="water.png" alt="Water" class="apple" id="apple">
            </div>
        </div>
    </div>

    <script>
    // สุ่มตำแหน่งต้นไม้ (knight) และแหล่งน้ำ (apple)
    function setRandomPosition() {
        const field = document.getElementById('field');
        const fieldWidth = field.offsetWidth;
        const fieldHeight = field.offsetHeight;

        // สุ่มตำแหน่งต้นไม้ (knight)
        const knight = document.getElementById('knight');
        const knightWidth = knight.offsetWidth;
        const knightHeight = knight.offsetHeight;
        const knightX = Math.random() * (fieldWidth - knightWidth);
        const knightY = Math.random() * (fieldHeight - knightHeight);
        knight.style.left = knightX + 'px';
        knight.style.top = knightY + 'px';

        // สุ่มตำแหน่งแหล่งน้ำ (apple)
        const apple = document.getElementById('apple');
        const appleWidth = apple.offsetWidth;
        const appleHeight = apple.offsetHeight;
        const appleX = Math.random() * (fieldWidth - appleWidth);
        const appleY = Math.random() * (fieldHeight - appleHeight);
        apple.style.left = appleX + 'px';
        apple.style.top = appleY + 'px';
    }

    // เรียกใช้ฟังก์ชันสุ่มตำแหน่งเมื่อโหลดหน้า
    window.onload = setRandomPosition;

    document.getElementById('check-answer').addEventListener('click', () => {
        const cssInput = document.getElementById('css-input').value; // รับคำสั่ง CSS จาก textarea
        const tree = document.querySelector('.knight'); // ต้นไม้
        const water = document.querySelector('.apple'); // แหล่งน้ำ

        try {
            // ใช้คำสั่ง CSS กับต้นไม้
            tree.style.cssText += cssInput;

            // ตรวจสอบว่าต้นไม้ชนกับแหล่งน้ำหรือไม่
            const treeRect = tree.getBoundingClientRect();
            const waterRect = water.getBoundingClientRect();

            if (
                treeRect.right >= waterRect.left &&
                treeRect.left <= waterRect.right &&
                treeRect.bottom >= waterRect.top &&
                treeRect.top <= waterRect.bottom
            ) {
                document.getElementById('result').textContent = 'Success! Arthur has reached the water!';
                // อัปเดต level เป็น 6
                <?php $_SESSION['level'] = 6; ?>
                // เปลี่ยนหน้าไปที่เกม level 6
                setTimeout(() => {
                    window.location.href = 'game6.php'; // ไปยังเกม Level 6
                }, 2000);
            } else {
                document.getElementById('result').textContent = 'Try again! Adjust your CSS!';
            }
        } catch (error) {
            document.getElementById('result').textContent = 'Invalid CSS!';
        }
    });
    </script>

</body>
</html>
