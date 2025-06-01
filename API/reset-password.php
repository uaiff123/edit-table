<?php
session_start();
require_once('connect.php'); // เชื่อมต่อฐานข้อมูล

$email = $_POST['email'] ?? '';
$oldPassword = $_POST['oldPassword'] ?? '';
$newPassword = $_POST['password'] ?? '';

if (empty($email) || empty($oldPassword) || empty($newPassword)) {
    echo json_encode(['success' => false, 'message' => 'กรุณากรอก Email, รหัสผ่านเก่า และรหัสผ่านใหม่ให้ครบ']);
    exit;
}

// เช็คว่ามี email นี้ในฐานข้อมูลไหม
$stmt = $conn->prepare("SELECT password FROM edit WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'ไม่พบ Email นี้ในระบบ']);
    exit;
}

$row = $result->fetch_assoc();
$hashedPassword = $row['password'];

// ตรวจสอบรหัสผ่านเก่า
if (!password_verify($oldPassword, $hashedPassword)) {
    echo json_encode(['success' => false, 'message' => 'รหัสผ่านเก่าไม่ถูกต้อง']);
    exit;
}

// แฮชรหัสผ่านใหม่
$newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

// อัพเดตรหัสผ่านใหม่
$updateStmt = $conn->prepare("UPDATE edit SET password = ? WHERE email = ?");
$updateStmt->bind_param("ss", $newHashedPassword, $email);

if ($updateStmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'เปลี่ยนรหัสผ่านเรียบร้อยแล้ว']);
} else {
    echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการอัพเดตรหัสผ่าน']);
}
?>
