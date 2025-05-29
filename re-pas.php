<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$token = bin2hex(random_bytes(25)); // กรณีอยากใช้ token ป้องกัน CSRF หรือสำหรับลิงก์อื่นๆ
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Reset Password</title>
  <link rel="stylesheet" href="css/bootstrap.min.css" />
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" />
<link rel="icon" href="img/logodatary.png" type="image/jpeg" sizes="16x16">

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

<body class="d-flex align-items-center py-4 bg-body-tertiary">
  <main class="form-signin w-100 m-auto">
    <form id="resetPasswordForm" class="p-3 border rounded bg-white" style="max-width: 400px; margin:auto;">
      <h1 class="h3 mb-3 fw-normal text-center">Reset Password</h1>

      <div class="form-floating mb-3">
        <input type="email" name="email" id="email" class="form-control" placeholder="name@example.com" required />
        <label for="email">Email address</label>
      </div>

      <div class="form-floating mb-3">
        <input type="password" name="password" id="password" class="form-control" placeholder="New Password" required minlength="8" />
        <label for="password">New Password</label>
      </div>
 <p>I don't want to change my password. <a href="signin.php?token=<?= $token ?>"> BACK </a> </p>
      <button class="btn btn-primary w-100 py-2" type="submit">Change Password</button>
    </form>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    const form = document.getElementById('resetPasswordForm');

    form.addEventListener('submit', function (e) {
      e.preventDefault();

      const formData = new FormData(form);

      axios.post('API/reset-password.php', formData)
        .then(res => {
          const data = res.data;
          if (data.success) {
            Swal.fire({
              icon: 'success',
              title: 'Success',
              text: data.message,
            }).then(() => {
              window.location.href = 'signin.php'; // เปลี่ยนเส้นทางหลังเปลี่ยนรหัสผ่านสำเร็จ
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: data.message,
            });
          }
        })
        .catch(err => {
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'เกิดข้อผิดพลาดในการเชื่อมต่อกับเซิร์ฟเวอร์',
          });
        });
    });
  </script>
</body>

</html>
