<?php 
require_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!preg_match('/^(?=.*[a-zA-Z])(?=.*\d)[a-zA-Z\d]+$/', $username)) {
        echo json_encode(['success' => false, 'message' => 'ชื่อผู้ใช้ต้องมีทั้งตัวอักษรและตัวเลข']);
        exit;
    }

    if (!preg_match('/^[a-zA-Z0-9._%+-]+@gmail\.com$/', $email)) {
        echo json_encode(['success' => false, 'message' => 'Email ต้องเป็น name@gmail.com เท่านั้น']);
        exit;
    }

    if (!preg_match('/^(?=.*\d).{8,}$/', $password)) {
        echo json_encode(['success' => false, 'message' => 'รหัสผ่านต้องมีอย่างน้อย 8 ตัว และมีตัวเลข']);
        exit;
    }

    $check = $conn->prepare("SELECT id FROM edit WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Email นี้ถูกใช้งานแล้ว']);
        exit;
    }

    $hash_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO edit (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hash_password);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'เพิ่มผู้ใช้สำเร็จ']);
    } else {
        echo json_encode(['success' => false, 'message' => 'เพิ่มผู้ใช้ไม่สำเร็จ']);
    }
}

?>