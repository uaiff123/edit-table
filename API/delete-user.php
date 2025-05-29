<?php
// api/delete-user.php
header('Content-Type: application/json');
require 'connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method ไม่ถูกต้อง']);
    exit;
}

$id = intval($_POST['id']);

// ตรวจสอบสถานะผู้ใช้ก่อนลบ
$stmt = $conn->prepare("SELECT status FROM edit WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    echo json_encode(['success' => false, 'message' => 'ไม่พบผู้ใช้']);
    exit;
}

if ($user['status'] === 'admin') {
    echo json_encode(['success' => false, 'message' => 'ไม่สามารถลบ admin ได้']);
    exit;
}

// ลบผู้ใช้
$deleteStmt = $conn->prepare("DELETE FROM edit WHERE id = ?");
$deleteStmt->bind_param("i", $id);
$success = $deleteStmt->execute();

if ($success) {
    echo json_encode(['success' => true, 'message' => 'ลบผู้ใช้สำเร็จ']);
} else {
    echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการลบ']);
}
