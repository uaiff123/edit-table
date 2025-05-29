<?php
session_start();
header('Content-Type: application/json');

require_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$email    = $_POST['email']    ?? '';
$password = $_POST['password'] ?? '';

if (empty($email) || empty($password)) {
    echo json_encode(['success' => false, 'message' => 'กรุณากรอกอีเมล และ รหัสผ่าน']);
    exit;
}

$stmt = $conn->prepare("SELECT id, email, password, status FROM edit WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'ไม่พบอีเมลของท่าน']);
    exit;
}

$user = $result->fetch_assoc();
if (!password_verify($password, $user['password'])) {
    echo json_encode(['success' => false, 'message' => 'รหัสผ่านไม่ถูกต้อง']);
    exit;
}

// เก็บ session
$_SESSION['id']     = $user['id'];
$_SESSION['email']  = $user['email'];
$_SESSION['status'] = $user['status'];  // จะเป็น "admin" หรือ "user"

echo json_encode([
    'success' => true,
    'message' => 'เข้าสู่ระบบสำเร็จ',
    'status'  => $user['status']
]);
exit;
