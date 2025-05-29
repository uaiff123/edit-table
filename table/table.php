<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once('../API/connect.php');

if (empty($_SESSION['id'])) {
    header("Location: ../nologin.php");
    exit;
}
$token = bin2hex(random_bytes(25));
$user_id = $_SESSION['id'];

$stmt = $conn->prepare("SELECT id, work_date, detail, time_start, time_end FROM work_table WHERE user_id = ? ORDER BY id DESC LIMIT 5");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$workData = [];
while ($row = $result->fetch_assoc()) {
    $workData[] = $row;
}
$stmt->close();

// เติมแถวให้ครบ 5
for ($i = count($workData); $i < 5; $i++) {
    $workData[] = ['work_date' => '', 'detail' => '', 'time_start' => '', 'time_end' => ''];
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
  <meta charset="UTF-8" />
  <title>จัดการข้อมูลงาน</title>
  <link rel="stylesheet" href="../css/bootstrap.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <link rel="icon" href="img/logodatary.png" type="image/jpeg" sizes="16x16">
  <script>
    window.addEventListener('pageshow', function () {
      if (!sessionStorage.getItem('reloaded')) {
        sessionStorage.setItem('reloaded', 'true');
        window.location.reload();
      } else {
        sessionStorage.removeItem('reloaded');
      }
    });
  </script>
</head>
<body>
  <div class="container mt-4">
    <a class="btn btn-primary mb-3" href="../index.php?token=<?= $token ?>">กลับหน้าแรก</a>

    <form method="post" action="../API/save_work.php" onsubmit="return validateBeforeSubmit();">
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>วันที่</th>
            <th>รายละเอียด</th>
            <th>เวลาเริ่ม</th>
            <th>เวลาเลิก</th>
            <th>การบันทึก</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($workData as $index => $row): ?>
            <tr>
              <td>
                <input type="date" name="work_date[]" class="form-control" value="<?= htmlspecialchars($row['work_date']) ?>">
              </td>
              <td>
                <input type="text" name="detail[]" class="form-control" value="<?= htmlspecialchars($row['detail']) ?>">
              </td>
              <td>
                <input type="time" name="time_start[]" class="form-control" value="<?= htmlspecialchars($row['time_start']) ?>">
              </td>
              <td>
                <input type="time" name="time_end[]" class="form-control" value="<?= htmlspecialchars($row['time_end']) ?>">
              </td>
              <td> 
                <input type="text" name="save" class="savv-color" v>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <button type="submit" class="btn btn-primary">💾 บันทึกทั้งหมด</button>
    </form>
  </div>

  <script>
    function validateBeforeSubmit() {
      const dates = document.getElementsByName('work_date[]');
      const details = document.getElementsByName('detail[]');
      const starts = document.getElementsByName('time_start[]');
      const ends = document.getElementsByName('time_end[]');

      let validCount = 0;

      for (let i = 0; i < dates.length; i++) {
        const d = dates[i].value.trim();
        const t = details[i].value.trim();
        const s = starts[i].value.trim();
        const e = ends[i].value.trim();

        // ถ้าใส่ครบทั้ง 4 ช่อง = เก็บไว้
        if (d && t && s && e) {
          validCount++;
        } else {
          // ล้างค่าทิ้ง จะไม่ถูกส่งไปบันทึก
          dates[i].name = '';
          details[i].name = '';
          starts[i].name = '';
          ends[i].name = '';
        }
      }

      if (validCount === 0) {
        alert('กรอก จำนวนให้ครบ');
        return false;
      }

      return true;
    }
  </script>
</body>
</html>
