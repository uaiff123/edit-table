<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;

    if ($id) {
        $stmt = $conn->prepare("DELETE FROM work_table WHERE id = ?");
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            header("Location: ../black-home.php?msg=ลบงานเรียบร้อย");
            exit;
        } else {
            echo "เกิดข้อผิดพลาดในการลบงาน";
        }
    } else {
        echo "ไม่มี ID งาน";
    }
} else {
    echo "Method ไม่ถูกต้อง";
}
