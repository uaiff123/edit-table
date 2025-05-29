<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once('connect.php');

if (empty($_SESSION['id'])) {
    header("Location: ../signin.php");
    exit;
}

$user_id = $_SESSION['id'];

$ids = $_POST['id'] ?? [];
$work_dates = $_POST['work_date'] ?? [];
$details = $_POST['detail'] ?? [];
$time_starts = $_POST['time_start'] ?? [];
$time_ends = $_POST['time_end'] ?? [];

for ($i = 0; $i < count($work_dates); $i++) {
    $id = isset($ids[$i]) ? intval($ids[$i]) : 0;
    $work_date = $work_dates[$i];
    $detail = $details[$i];
    $time_start = $time_starts[$i];
    $time_end = $time_ends[$i];

    if ($id > 0) {
        $stmt = $conn->prepare("UPDATE work_table SET work_date = ?, detail = ?, time_start = ?, time_end = ? WHERE id = ? AND user_id = ?");
        if ($stmt) {
            $stmt->bind_param("ssssii", $work_date, $detail, $time_start, $time_end, $id, $user_id);
            $stmt->execute();
            $stmt->close();
        }
    } else {
        if (!empty($work_date) || !empty($detail) || !empty($time_start) || !empty($time_end)) {
            $stmt = $conn->prepare("INSERT INTO work_table (user_id, work_date, detail, time_start, time_end) VALUES (?, ?, ?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("issss", $user_id, $work_date, $detail, $time_start, $time_end);
                $stmt->execute();
                $stmt->close();
            }
        }
    }
}

header("Location: ../table/table.php");
exit;
?>
