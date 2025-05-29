<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once(__DIR__ . '/API/connect.php');

if (empty($_SESSION['id'])) {
    header("Location: signin.php");
    exit();
}
$token = bin2hex(random_bytes(25));
$user_id = $_SESSION['id'];

$sql = "SELECT work_id, old_date, old_detail, old_start, old_end, updated_at 
        FROM work_history 
        WHERE user_id = ? 
        ORDER BY updated_at DESC";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Prepare failed: (" . $conn->errno . ") " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$histories = [];
while ($row = $result->fetch_assoc()) {
    $histories[] = $row;
}
$stmt->close();
?>

<!-- HTML แสดงผลเหมือนเดิม -->
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>ประวัติการแก้ไขงาน</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script>
    window.addEventListener('pageshow', function(event) {
    // เช็คว่าเคยรีเฟรชแล้วรอบนึงไหม
    if (!sessionStorage.getItem('reloaded')) {
        sessionStorage.setItem('reloaded', 'true');
        window.location.reload();
    } else {
 
        sessionStorage.removeItem('reloaded');
    }
});
</script>
        <link rel="icon" href="img/logodatary.png" type="image/jpeg" sizes="16x16">
    <style>
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .card-header {
            background-color: #007bff;
            color: white;
            border-radius: 15px 15px 0 0;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <a href="index.php?token=<?= $token ?>" class="btn btn-secondary mb-4">&larr; กลับหน้าแรก</a>
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">ประวัติการแก้ไขงานของคุณ</h4>
            </div>
            <div class="card-body">
                <?php if (empty($histories)): ?>
                    <div class="alert alert-info">ไม่มีประวัติการแก้ไขข้อมูล</div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th>#</th>
                                    <th>ประวัติวันที่</th>
                                    <th>ประวัติกาารรายละเอียดเดิม</th>
                                    <th>ประวัติเวลาเริ่มเดิม</th>
                                    <th>ประวัิติเวลาเริ่มเดิมเ</th>
                                    <th>เวลาที่แก้ไข</th>
                                </tr>
                            </thead>
                            <tbody >
                                <?php foreach ($histories as $index => $row): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= htmlspecialchars($row['old_date']) ?></td>
                                        <td><?= htmlspecialchars($row['old_detail']) ?></td>
                                        <td><?= htmlspecialchars($row['old_start']) ?></td>
                                        <td><?= htmlspecialchars($row['old_end']) ?></td>
                                        <td><?= htmlspecialchars($row['updated_at']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
