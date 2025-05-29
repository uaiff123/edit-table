<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'API/connect.php';

if (!isset($_SESSION['id'])) {
    header('Location: nologin.php');
    exit;
}

$userId = $_SESSION['id'];
$userEmail = $_SESSION['email'];
$userStatus = $_SESSION['status'];
$userName = $_SESSION['id'];

$allUsers = $conn->query("SELECT * FROM edit")->fetch_all(MYSQLI_ASSOC);

// ถ้าเป็น admin ให้เห็นทุกงาน ถ้าไม่ใช่ ให้เห็นเฉพาะของตัวเอง
if ($userStatus === 'admin') {
    $workQuery = $conn->query("SELECT * FROM work_table")->fetch_all(MYSQLI_ASSOC);
} else {
    $stmt = $conn->prepare("SELECT * FROM work_table WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $workQuery = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>จัดการผู้ใช้</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <script>
    window.addEventListener('pageshow', function(event) {
    // เช็คว่าเคยรีเฟรชแล้วรอบนึงไหม
    if (!sessionStorage.getItem('reloaded')) {
        sessionStorage.setItem('reloaded', 'true');
        window.location.reload();
    } else {
        // เคยรีเฟรชแล้ว ล้าง sessionStorage เพื่อให้รอบถัดไปโหลดใหม่ปกติ
        sessionStorage.removeItem('reloaded');
    }
});
</script>
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="card shadow-lg border-0">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
      <h4 class="mb-0">จัดการบัญชีผู้ใช้</h4>
      <div>
        👤 <?= htmlspecialchars($userName) ?> (<?= $userStatus ?>)
        <a href="nologin.php" class="btn btn-outline-light btn-sm ms-3">
          <i class="bi bi-box-arrow-right"></i> ออกจากระบบ
        </a>
      </div>
    </div>

    <div class="card-body p-4">
      <h5>บัญชีผู้ใช้ทั้งหมด</h5>
      <table class="table table-hover align-middle">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>ID</th>
            <th>ชื่อ</th>
            <th>สถานะ</th>
            <th>อีเมล</th>
            <th class="text-center">จัดการ</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($allUsers as $i => $user): ?>
            <tr>
              <td><?= $i + 1 ?></td>
              <td><?= $user['id'] ?></td>
              <td><?= htmlspecialchars($user['name']) ?></td>
              <td>
                <span class="badge bg-<?= $user['status'] === 'admin' ? 'primary' : 'secondary' ?>">
                  <?= $user['status'] ?>
                </span>
              </td>
              <td><?= htmlspecialchars($user['email']) ?></td>
              <td class="text-center">
                <div class="d-flex justify-content-center gap-2">
                  <?php if ($user['status'] !== 'admin'): ?>
                    <a href="API/edit-user.php?id=<?= $user['id'] ?>" class="btn btn-warning btn-sm">
                      <i class="bi bi-pencil-square"></i> แก้ไข
                    </a>
                    <button onclick="deleteUser(<?= $user['id'] ?>)" class="btn btn-danger btn-sm">
                      <i class="bi bi-trash"></i> ลบ
                    </button>
                  <?php else: ?>
                    <span class="text-muted">-</span>
                  <?php endif; ?>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php if (empty($allUsers)): ?>
            <tr><td colspan="6" class="text-center text-muted">ไม่มีบัญชีผู้ใช้</td></tr>
          <?php endif; ?>
        </tbody>
      </table>

      <h5 class="mt-5">ตารางงานทั้งหมด</h5>
      <table class="table table-bordered">
        <thead class="table-light">
          <tr>
            <th>ข้อมูลมลที่</th>
            <th>ชื่อผู้ใช้</th>
            <th>รายละเอียด</th>
            <th>วันที่</th>
            <th>เริ่ม</th>
            <th>สิ้นสุด</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($workQuery as $row): ?>
            <tr>
              <td><?= $row['id'] ?></td>
              <td><?= $row['user_id'] ?></td>
              <td><?= $row['detail'] ?></td>
              <td><?= $row['work_date'] ?></td>
              <td><?= $row['time_start'] ?></td>
              <td><?= $row['time_end'] ?></td>
            </tr>
          <?php endforeach; ?>
          <?php if (empty($workQuery)): ?>
            <tr><td colspan="6" class="text-center text-muted">ไม่พบข้อมูลงาน</td></tr>
          <?php endif; ?>
        </tbody>
      </table>

    </div>
  </div>
</div>

<script>
function deleteUser(id) {
  if (confirm("คุณแน่ใจหรือไม่ว่าต้องการลบผู้ใช้นี้?")) {
    fetch('api/delete-user.php', {
      method: 'POST',
      headers: {'Content-Type': 'application/x-www-form-urlencoded'},
      body: 'id=' + id
    }).then(res => res.json()).then(data => {
      alert(data.message);
      if (data.success) location.reload();
    });
  }
}
</script>

</body>
</html>
