<?php
require_once('connect.php');

if (!isset($_POST['id'], $_POST['work_date'], $_POST['detail'], $_POST['time_start'], $_POST['time_end'])) {
    echo json_encode(['success' => false, 'message' => 'ข้อมูลไม่ครบ']);
    exit;
}

// ล้างช่องว่าง
$id = trim($_POST['id']);
$date = trim($_POST['work_date']);
$detail = trim($_POST['detail']);
$start = trim($_POST['time_start']);
$end = trim($_POST['time_end']);

// ตรวจสอบว่าค่าทั้งหมดไม่ว่าง
if (empty($id) || empty($date) || empty($detail) || empty($start) || empty($end)) {
    echo json_encode(['success' => false, 'message' => 'ข้อมูลบางส่วนว่างเปล่า']);
    exit;
}

// อัปเดตข้อมูล
$stmt = $conn->prepare("UPDATE work_table SET work_date = ?, detail = ?, time_start = ?, time_end = ? WHERE id = ?");
$stmt->bind_param("ssssi", $date, $detail, $start, $end, $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'อัปเดตสำเร็จ']);
} else {
    echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาดขณะอัปเดต']);
}

$stmt->close();
$conn->close();
