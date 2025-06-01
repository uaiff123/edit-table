<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'API/connect.php';

if (!isset($_SESSION['id'])) {
    header('Location: signin.php');
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
  <title>จัดการผู้ใช้ & งานทั้งหมด</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <script>
    window.addEventListener('pageshow', function(event) {
      if (!sessionStorage.getItem('reloaded')) {
          sessionStorage.setItem('reloaded', 'true');
          window.location.reload();
      } else {
          sessionStorage.removeItem('reloaded');
      }
    });

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
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="card shadow-lg border-0">
    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
      <h4 class="mb-0">จัดการบัญชีผู้ใช้</h4>
      <div>
        👤 
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
      <th>จัดการ</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($workQuery as $row): ?>
      <tr>
        <form method="post" action="API/ed.php" onsubmit="return confirm('บันทึกข้อมูลนี้ใช่ไหม?');">
          <td>
            <?= $row['id'] ?>
            <input type="hidden" name="id" value="<?= $row['id'] ?>">
          </td>
          <td>
            <input type="text" name="user_id" value="<?= $row['user_id'] ?>" readonly class="form-control-plaintext" style="width:60px;">
          </td>
          <td>
            <textarea name="detail" class="form-control" rows="2" required><?= htmlspecialchars($row['detail']) ?></textarea>
          </td>
          <td>
            <input type="date" name="work_date" value="<?= $row['work_date'] ?>" class="form-control" required>
          </td>
          <td>
            <input type="time" name="time_start" value="<?= $row['time_start'] ?>" class="form-control" required>
          </td>
          <td>
            <input type="time" name="time_end" value="<?= $row['time_end'] ?>" class="form-control" required>
          </td>
          <td class="d-flex gap-1">
            <button type="submit" class="btn btn-warning btn-sm">
              <i class="bi bi-save"></i> บันทึก
            </button>
        </form>
            <form method="post" action="API/dl.php" onsubmit="return confirm('คุณแน่ใจหรือว่าต้องการลบ?');">
              <input type="hidden" name="id" value="<?= $row['id'] ?>">
              <button type="submit" class="btn btn-danger btn-sm">
                <i class="bi bi-trash"></i> ลบ
              </button>
            </form>
          </td>
      </tr>
    <?php endforeach; ?>
    <?php if (empty($workQuery)): ?>
      <tr><td colspan="7" class="text-center text-muted">ไม่พบข้อมูลงาน</td></tr>
    <?php endif; ?>
  </tbody>
</table>


    </div>
  </div>
</div>

</body>
</html>
