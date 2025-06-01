<?php
session_start();
require_once('API/connect.php');

if (empty($_SESSION['id'])) {
    header("Location: signin.php");
    exit();
    $token = bin2hex(random_bytes(25));
}

$user_id = $_SESSION['id'];

// ดึงข้อมูลงานทั้งหมดของ user นี้
$sql = "SELECT id, work_date, detail, time_start, time_end FROM work_table WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$works = [];
while ($row = $result->fetch_assoc()) {
    $works[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8" />
    <title>แก้ไขงานของคุณ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="btn btn-primary">
    <a style="color: white;" href="index.php"> Exit</a>
</div>
    <h3>แก้ไขงานของคุณ</h3>
    <?php if (empty($works)): ?>
        <div class="alert alert-info">คุณยังไม่มีงานในระบบ</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-primary">
                    <tr>
                        <th>ID งาน</th>
                        <th>วันที่</th>
                        <th>รายละเอียด</th>
                        <th>เวลาเริ่ม</th>
                        <th>เวลาสิ้นสุด</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
              <tbody>
<?php foreach ($works as $work): ?>
    <tr>
        <form method="post" action="API/update-work.php">
            <input type="hidden" name="id" value="<?= $work['id'] ?>">
            <td><?= $work['id'] ?></td>
            <td><input type="date" name="work_date" class="form-control" value="<?= $work['work_date'] ?>" required></td>
            <td><textarea name="detail" class="form-control" required><?= htmlspecialchars($work['detail']) ?></textarea></td>
            <td><input type="time" name="time_start" class="form-control" value="<?= $work['time_start'] ?>" required></td>
            <td><input type="time" name="time_end" class="form-control" value="<?= $work['time_end'] ?>" required></td>
            <td>
                <div class="d-flex flex-column flex-md-row gap-2">
                    <button type="submit" class="btn btn-warning btn-sm">บันทึก</button>
        </form>
        <form method="post" action="API/delete-work.php" onsubmit="return confirm('คุณแน่ใจว่าต้องการลบงานนี้?');">
            <input type="hidden" name="id" value="<?= $work['id'] ?>">
                    <button type="submit" class="btn btn-danger btn-sm">ลบ</button>
                </div>
            </td>
        </form>
    </tr>
<?php endforeach; ?>
</tbody>

            </table>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
