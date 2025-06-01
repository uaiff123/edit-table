<?php 
print '<script>
    window.addEventListener("pageshow", function(event) {
        if (!sessionStorage.getItem("reloaded")) {
            sessionStorage.setItem("reloaded", "true");
            window.location.reload();
        } else {
            sessionStorage.removeItem("reloaded");
        }
    });
</script>';
?>

<?php
require 'connect.php';

if (!isset($_GET['id'])) {
    header('Location: ../signin.php');
    exit;
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM edit WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user || $user['status'] === 'admin') {
    echo "ไม่สามารถแก้ไข admin ได้";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $status = trim($_POST['status']);

    $updateStmt = $conn->prepare("UPDATE edit SET name = ?, email = ?, status = ? WHERE id = ?");
    $updateStmt->bind_param("sssi", $name, $email, $status, $id);
    if ($updateStmt->execute()) {
        header("Location: ../black-home.php");
        exit;
    } else {
        $error = "เกิดข้อผิดพลาดในการอัปเดต";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8">
  <title>แก้ไขผู้ใช้</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
  <div class="card shadow">
    <div class="card-header bg-warning">
      <h4>แก้ไขข้อมูลผู้ใช้</h4>
    </div>
    <div class="card-body">
      <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
      <?php endif; ?>
      <form method="POST">
        <div class="mb-3">
          <label class="form-label">ชื่อ</label>
          <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">อีเมล</label>
          <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">สถานะ</label>
          <select name="status" class="form-select" required>
            <option value="user" <?= $user['status'] === 'user' ? 'selected' : '' ?>>user</option>
            <option value="admin" <?= $user['status'] === 'admin' ? 'selected' : '' ?>>admin</option>
          </select>
        </div>
        <div class="d-flex justify-content-between">
          <a href="../black-home.php" class="btn btn-secondary">ย้อนกลับ</a>
          <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
        </div>
      </form>
    </div>
  </div>
</div>
</body>
</html>
