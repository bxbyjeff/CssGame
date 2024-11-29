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
    <title>CSS Adventure Game - Level 2</title>
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
            top: 5%;
            left: 80%;
            width: 50px;
            height: auto;
        }

        .apple {
            position: absolute;
            top: 85%;
            left: 10%;
            width: 40px;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="game-container">
        <!-- Game Info Section -->
        <div class="game-info">
            <h2>Level 2</h2>
            <p>Heroes' health:</p>
            <div class="health-bar">
                <div class="health" style="width: 30%;"></div>
            </div>
            <p>30/100 ❤️</p>
            <p>
                Arthur has traveled far, but the journey has become harder. The evil brothers have set traps! 
                Help Arthur navigate the tricky terrain to reach the water source.
            </p>
            <div class="css-editor">
                <pre>#field {</pre>
                <textarea id="css-input" placeholder="Type your answer here..."></textarea>
                <pre>}</pre>
                <button id="check-answer">Check Answer</button>
                <p id="result"></p> <!-- Display results -->
            </div>
        </div>

        <!-- Game Field Section -->
        <div class="game-field">
            <div id="field" class="field">
                <!-- Knight (Tree) and Apple (Water) -->
                <img src="tree.png" alt="Tree" class="knight">
                <img src="water.png" alt="Water" class="apple">
            </div>
        </div>
    </div>
    <script>
    document.getElementById('check-answer').addEventListener('click', () => {
        const cssInput = document.getElementById('css-input').value;
        const tree = document.querySelector('.knight');
        const water = document.querySelector('.apple');

        try {
            tree.style.cssText += cssInput;
            const treeRect = tree.getBoundingClientRect();
            const waterRect = water.getBoundingClientRect();

            if (
                treeRect.right >= waterRect.left &&
                treeRect.left <= waterRect.right &&
                treeRect.bottom >= waterRect.top &&
                treeRect.top <= waterRect.bottom
            ) {
                document.getElementById('result').textContent = 'Success! Arthur reached the water!';
            } else {
                document.getElementById('result').textContent = 'Not yet! Try adjusting your CSS!';
            }
        } catch (error) {
            document.getElementById('result').textContent = 'Invalid CSS!';
        }
    });
    </script>
</body>
</html>
