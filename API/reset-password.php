<?php
session_start();
require_once('connect.php'); // เชื่อมต่อฐานข้อมูล

// รับค่า POST
$email = $_POST['email'] ?? '';
$newPassword = $_POST['password'] ?? '';

// ตรวจสอบข้อมูลเบื้องต้น
if (empty($email) || empty($newPassword)) {
    echo json_encode(['success' => false, 'message' => 'Email หรือ รหัสผ่านใหม่ ห้ามว่าง']);
    exit;
}

// ตรวจสอบว่า email มีอยู่ในฐานข้อมูลหรือไม่
$stmt = $conn->prepare("SELECT id FROM edit WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'ไม่พบ Email นี้ในระบบ']);
    exit;
}

// เข้ารหัสรหัสผ่านก่อนบันทึก (แนะนำใช้ password_hash)
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

// อัพเดต password
$updateStmt = $conn->prepare("UPDATE edit SET password = ? WHERE email = ?");
$updateStmt->bind_param("ss", $hashedPassword, $email);

if ($updateStmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'เปลี่ยนรหัสผ่านเรียบร้อยแล้ว']);
} else {
    echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการอัพเดตรหัสผ่าน']);
}
