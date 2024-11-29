<?php
session_start();

// ตรวจสอบว่ามีการส่งค่า decrease มาหรือไม่
if (isset($_GET['decrease'])) {
    $decrease = intval($_GET['decrease']);
    
    // ถ้ายังไม่มีค่า health ให้เริ่มที่ 100
    if (!isset($_SESSION['health'])) {
        $_SESSION['health'] = 100;
    }
    
    // ลด health ตามค่าที่ส่งมา
    $_SESSION['health'] = max(0, $_SESSION['health'] - $decrease);
    
    // ถ้า health = 0 ให้รีเซ็ตเป็น 100
    if ($_SESSION['health'] <= 0) {
        $_SESSION['health'] = 100;
    }
    
    // ส่งค่า health กลับเป็น JSON
    header('Content-Type: application/json');
    echo json_encode([
        'health' => $_SESSION['health'],
        'gameOver' => $_SESSION['health'] === 100 // เพิ่มสถานะว่าเพิ่งรีเซ็ตหรือไม่
    ]);
    exit;
}

// รีเซ็ต health
if (isset($_GET['reset'])) {
    $_SESSION['health'] = 100;
    header('Content-Type: application/json');
    echo json_encode(['health' => $_SESSION['health']]);
    exit;
}
?>
