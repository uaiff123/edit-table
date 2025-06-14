<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
 
require_once('API/connect.php');

if (!empty($_SESSION['id'])) {
    header("Location: index.php"); 
    exit();
}


$token = bin2hex(random_bytes(25));
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign-up-datary</title>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="mycss/sign-up.css">
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
  <style>
    #messageBox {
      display: none;
      position: fixed;
      top: 20%;
      left: 50%;
      transform: translate(-50%, -50%);
      background: rgba(0, 0, 0, 0.8);
      color: #fff;
      padding: 1rem 2rem;
      border-radius: 8px;
      font-size: 1.2rem;
      font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
      font-weight: 800;
      text-align: center;
      z-index: 1000;
      opacity: 0;
      transition: opacity 0.3s ease;
    }

    #messageBox.show {
      display: block;
      opacity: 1;
    }

    #messageBox.error {
      background: rgba(200, 0, 0, 0.9);
    }

    #messageBox.success {
      background: rgba(0, 128, 0, 0.9);
    }
  </style>
</head>

<body>

  <body class="d-flex align-items-center py-4 bg-body-tertiary"> <svg xmlns="http://www.w3.org/2000/svg" class="d-none">
      <symbol id="check2" viewBox="0 0 16 16">
        <path
          d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z">
        </path>
      </symbol>
      <symbol id="circle-half" viewBox="0 0 16 16">
        <path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z"></path>
      </symbol>
      <symbol id="moon-stars-fill" viewBox="0 0 16 16">
        <path
          d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z">
        </path>
        <path
          d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z">
        </path>
      </symbol>
      <symbol id="sun-fill" viewBox="0 0 16 16">
        <path
          d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z">
        </path>
      </symbol>
    </svg>
    <div id="messageBox"> </div>
    <main class="form-signin w-100 m-auto">

      <form id="signupform"> <img class="mb-4" src="img/logodatary.png" alt="" width="100" height="100">

        <h1 class="h3 mb-3 fw-normal">
          Datary  SignUp</h1>
        <div class="u">
          <div class="form-floating"> <input type="email" name="email" class="form-control" id="floatingemail"
              placeholder="email" name="email"><label for="floatingemill">Email </label> </div>
        </div>
        <div class="t">
          <div class="form-floating"> <input type="text" name="name" class="form-control" id="floatingInput"
              placeholder="username" name="username"> <label for="floatingInput">Username</label> </div>
        </div>
        <div class="o">
          <div class="form-floating"> <input type="password" name="password" class="form-control" id="floatingPassword"
              placeholder="Password"> <label for="floatingPassword">Password</label> </div>
        </div>
        </label> </div>
        <button class="btn btn-primary w-100 py-2" type="submit">Sign Up</button><br><br>
        <p> you is a member ? <a href="signin.php?token=<?= $token ?>">sign in</a></p>
        <br>
        <p class="mt-5 mb-3 text-body-secondary">&copy; 2025</p>
      </form>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
      const form = document.getElementById('signupform');
      const box = document.getElementById('messageBox');

      form.addEventListener('submit', function (event) {
        event.preventDefault();
        const data = new FormData(form);

        axios.post('API/insert.php', data, { withCredentials: true })
          .then(response => {
            const res = response.data;

            // แสดงข้อความในกล่องตรงกลาง
            box.textContent = res.message;
            box.className = 'show ' + (res.success ? 'success' : 'error');

            // หลัง 1.5 วินาที ซ่อนกล่องและ redirect ถ้าสำเร็จ
            setTimeout(() => {
              box.classList.remove('show');
              if (res.success) {
                window.location.href = 'signin.php';
              }
            }, 1500);
          })
          .catch(error => {
            console.error('Signup error:', error);
            box.textContent = 'USER ไม่มีความปลอดภัย';
            box.className = 'show error';
            setTimeout(() => box.classList.remove('show'), 1500);
          });
      });

      // ป้องกัน back ย้อนลึกเกินหน้าเดียว (ถ้าต้องการ)
      window.history.pushState(null, null, window.location.href);
      window.onpopstate = function () {
        window.history.go(1);
      };
    </script>



  </body>

</html>