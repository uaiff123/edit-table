<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('API/connect.php');

$token = bin2hex(random_bytes(25));
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Sign-in-datary</title>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" />
  <link rel="stylesheet" href="css/bootstrap.min.css" />
  <link rel="stylesheet" href="mycss/sign-in.css" />
  <link rel="icon" href="img/logodatary.png" type="image/jpeg" sizes="16x16" />
  <script>
    window.addEventListener('pageshow', function (event) {
      if (!sessionStorage.getItem('reloaded')) {
        sessionStorage.setItem('reloaded', 'true');
        window.location.reload();
      } else {
        sessionStorage.removeItem('reloaded');
      }
    });
  </script>
  <style>
    #messageBox {
      display: none;
      position: fixed;
      top: 50%;
      left: 50%;
      margin-top: -400px;
      transform: translate(-50%, -50%);
      background: rgba(0, 0, 0, 0.8);
      color: #fff;
      padding: 1rem 2rem;
      border-radius: 8px;
      font-size: 1.2rem;
      font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen,
        Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
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

<body class="d-flex align-items-center py-4 bg-body-tertiary">
  <div id="messageBox"></div>
  <main class="form-signin w-100 m-auto">
    <form id="loginform">
      <img class="mb-4" src="img/logodatary.png" alt="" width="50" height="50" />
      <h1 class="h3 mb-3 fw-normal">Please Sign In</h1>
      <div class="space-bar">
        <div class="form-floating">
          <input
            type="email"
            name="email"
            class="form-control"
            id="floatingInput"
            placeholder="name@example.com"
            required
          />
          <label for="floatingInput">Email address</label>
        </div>
      </div>
      <div class="space">
        <div class="form-floating">
          <input
            type="password"
            name="password"
            class="form-control"
            id="floatingPassword"
            placeholder="Password"
            required
          />
          <label for="floatingPassword">Password</label>
        </div>
      </div>

      <button class="btn btn-primary w-100 py-2" type="submit">Sign in</button>
      <br /><br />
      <p>not yet a member ? <a href="signup.php?token=<?= $token ?>">sign up </a></p>
      <p><a href="re-pas.php?token=<?= $token ?>">Forgot your password? </a></p>
      <br />
      <p>Don't want to apply for membership <a href="nologin.php?token=<?= $token ?>"> BACK </a></p>

      <p class="mt-5 mb-3 text-body-secondary">&copy; 2025</p>
    </form>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
  const form = document.getElementById('loginform');
  const box  = document.getElementById('messageBox');

  form.addEventListener('submit', function(event) {
    event.preventDefault();
    const data = new FormData(form);

    axios.post('API/login.php', data, { withCredentials: true })
      .then(response => {
        const res = response.data;
        box.textContent = res.message;
        box.className   = 'show ' + (res.success ? 'success' : 'error');

        setTimeout(() => {
          box.classList.remove('show');
          if (res.success) {
            // ตรวจสอบ status
            if (res.status === 'admin') {
              window.location.href = 'black-home.php';
            } else {
              window.location.href = 'index.php';
            }
          }
        }, 1000);
      })
      .catch(() => {
        box.textContent = 'เกิดข้อผิดพลาดในการติดต่อเซิร์ฟเวอร์';
        box.className   = 'show error';
        setTimeout(() => box.classList.remove('show'), 1000);
      });
  });

    window.history.pushState(null, null, window.location.href);
    window.onpopstate = function () {
      window.history.go(1);
    };
  </script>

</body>

</html>
