<?php
session_start();
require_once('connect.php');

if (empty($_SESSION['id'])) {
    header("Location: ../signin.php");
    exit();
}

$user_id = $_SESSION['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $work_date = $_POST['work_date'];
    $detail = $_POST['detail'];
    $time_start = $_POST['time_start'];
    $time_end = $_POST['time_end'];

    // ตรวจสอบว่าเป็นเจ้าของข้อมูล
    $check = $conn->prepare("SELECT id FROM work_table WHERE id = ? AND user_id = ?");
    $check->bind_param("ii", $id, $user_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows === 0) {
        echo "คุณไม่มีสิทธิ์แก้ไขรายการนี้";
        exit();
    }

    $sql = "UPDATE work_table SET work_date = ?, detail = ?, time_start = ?, time_end = ? WHERE id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssii", $work_date, $detail, $time_start, $time_end, $id, $user_id);

    if ($stmt->execute()) {
        header("Location: ../history.php?success=1");
        exit();
    } else {
        echo "เกิดข้อผิดพลาดในการอัปเดต: " . $conn->error;
    }
}
