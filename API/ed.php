<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $detail = $_POST['detail'] ?? '';
    $work_date = $_POST['work_date'] ?? '';
    $time_start = $_POST['time_start'] ?? '';
    $time_end = $_POST['time_end'] ?? '';

    if (!$id) {
        die("ไม่มี ID งาน");
    }

    // Debug ค่า input
    // var_dump($id, $detail, $work_date, $time_start, $time_end); exit;

    // Prepare statement
    $stmt = $conn->prepare("UPDATE work_table SET detail = ?, work_date = ?, time_start = ?, time_end = ? WHERE id = ?");
    if (!$stmt) {
        die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
    }

    $bind = $stmt->bind_param("ssssi", $detail, $work_date, $time_start, $time_end, $id);
    if (!$bind) {
        die("Bind param failed: (" . $stmt->errno . ") " . $stmt->error);
    }

    $exec = $stmt->execute();
    if ($exec) {
        header("Location: ../black-home.php?msg=แก้ไขงานเรียบร้อย");
        exit;
    } else {
        die("Execute failed: (" . $stmt->errno . ") " . $stmt->error);
    }
} else {
    die("Method ไม่ถูกต้อง");
}
