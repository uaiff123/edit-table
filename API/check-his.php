<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if ($old && ($old['work_date'] !== $new_date || $old['detail'] !== $new_detail || $old['time_start'] !== $new_start || $old['time_end'] !== $new_end)) {
    // insert history and here
}

require_once('connect.php');


if (empty($_SESSION['id'])) {
    header("Location: ../signin.php");
    exit();
}

$user_id = $_SESSION['id'] ?? [];
$work_ids = $_POST['id'] ?? [];
$dates = $_POST['work_date'] ?? [];
$details = $_POST['detail'] ?? [];
$starts = $_POST['time_start'] ?? [];
$ends = $_POST['time_end'] ?? [];

for ($i = 0; $i < count($work_ids); $i++) {
    $id = $work_ids[$i];
    $new_date = $dates[$i];
    $new_detail = $details[$i];
    $new_start = $starts[$i];
    $new_end = $ends[$i];

    if (!empty($id)) {
        // คิวรีข้อมูลเก่า
        $stmt_old = $conn->prepare("SELECT work_date, detail, time_start, time_end FROM work_table WHERE id = ? AND user_id = ?");
        $stmt_old->bind_param("ii", $id, $user_id);
        $stmt_old->execute();
        $result_old = $stmt_old->get_result();
        $old = $result_old->fetch_assoc();
        $stmt_old->close();

        if ($old && ($old['work_date'] !== $new_date || $old['detail'] !== $new_detail || $old['time_start'] !== $new_start || $old['time_end'] !== $new_end)) {
            // เก็บประวัติก่อนแก้ไข
            $stmt_log = $conn->prepare("INSERT INTO work_history (id, user_id, old_date, old_detail, old_start, old_end, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
            $stmt_log->bind_param("iissss", $user_id, $id, $old['work_date'], $old['detail'], $old['time_start'], $old['time_end'], $old['updated_at']);
            $stmt_log->execute();
            $stmt_log->close();

            // อัปเดตข้อมูลใหม่

            $stmt_update = $conn->prepare("UPDATE work_table SET work_date = ?, detail = ?, time_start = ?, time_end = ? WHERE id = ? AND user_id = ?");
            $stmt_update->bind_param("ssssii", $new_date, $new_detail, $new_start, $new_end, $id, $user_id);
            $stmt_update->execute();
            $stmt_update->close();
        }
    }
}

header("Location: ../history.php?updated=1");
exit();
