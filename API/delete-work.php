<?php
session_start();
require_once('connect.php');

if (empty($_SESSION['id'])) {
    header("Location: ../signin.php");
    exit();
}

$user_id = $_SESSION['id'];
$id = $_POST['id'];

$sql = "DELETE FROM work_table WHERE id = ? AND user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $user_id);

if ($stmt->execute()) {
    header("Location: ../history.php?deleted=1");
    exit();
} else {
    echo "เกิดข้อผิดพลาดในการลบ: " . $conn->error;
}
