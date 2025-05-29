
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('API/connect.php');


if (empty($_SESSION['email'])) {
    header("Location: signin.php"); 
    exit();
}
$token = bin2hex(random_bytes(25));
?>


<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Astro v5.7.10">
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
    <title>Home Datary</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="mycss/navbar-static.css">
    <style>
        nav {
            background-color: rgb(0, 117, 252);
            display: inline-flex;
            width: 100%;
            
        }
        .container {
            background-image: url(https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRSwJMpWUVGxQvGzK6KqKGoHSMqu_yETYTmZw&s);
        }

        h5 {
            color:rgb(255, 255, 255);
        }
        h5 img {
           margin-left: -20px; 
        }

        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem
            }
        }

        .b-example-divider {
            width: 100%;
            height: 3rem;
            background-color: #0000001a;
            border: solid rgba(0, 0, 0, .15);
            border-width: 1px 0;
            box-shadow: inset 0 .5em 1.5em #0000001a, inset 0 .125em .5em #00000026
        }

        .b-example-vr {
            flex-shrink: 0;
            width: 1.5rem;
            height: 100vh
        }

        .bi {
            vertical-align: -.125em;
            fill: currentColor
        }

        .nav-scroller {
            position: relative;
            z-index: 2;
            height: 2.75rem;
            overflow-y: hidden
        }

        .nav-scroller .nav {
            display: flex;
            flex-wrap: nowrap;
            padding-bottom: 1rem;
            margin-top: -1px;
            overflow-x: auto;
            text-align: center;
            white-space: nowrap;
            -webkit-overflow-scrolling: touch
        }

        .btn-bd-primary {
            --bd-violet-bg:
                #712cf9;
            --bd-violet-rgb: 112.520718, 44.062154, 249.437846;
            --bs-btn-font-weight: 600;
            --bs-btn-color:
                var(--bs-white);
            --bs-btn-bg: var(--bd-violet-bg);
            --bs-btn-border-color: var(--bd-violet-bg);
            --bs-btn-hover-color:
                var(--bs-white);
            --bs-btn-hover-bg: #6528e0;
            --bs-btn-hover-border-color: #6528e0;
            --bs-btn-focus-shadow-rgb:
                var(--bd-violet-rgb);
            --bs-btn-active-color: var(--bs-btn-hover-color);
            --bs-btn-active-bg:
                #5a23c8;
            --bs-btn-active-border-color: #5a23c8
        }

        .bd-mode-toggle {
            z-index: 1500
        }

        .bd-mode-toggle .bi {
            width: 1em;
            height: 1em
        }

        .bd-mode-toggle .dropdown-menu .active .bi {
            display: block !important
        }

        .io {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            font-size: 20px;
            color: white;
            font-weight: 600;
        }
        h2 {
            color: #ffffff;
        }
    </style>
</head>

<body>

    <nav class="nav-new">
      
               
                <?php
                echo "<h2>Datary</h2>";
require_once 'API/connect.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ตรวจสอบว่าเข้าสู่ระบบแล้วหรือไม่
if (!isset($_SESSION['id'])) {
    header("Location: signin.php");
    exit();
}

$userId = $_SESSION['id'];

// ดึงข้อมูลผู้ใช้จากฐานข้อมูล
$stmt = $conn->prepare("SELECT name FROM edit WHERE id = ?");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $name = $row['name'];
    echo " <h5>Name &nbsp;" . htmlspecialchars($name);
} else {
    echo "ไม่พบชื่อผู้ใช้";
}
$stmt->close();
?></h5>
            <?php if (isset($_SESSION['id'])) {echo ' <h5>online <img style="width: 60px; height: 35px;" src="img/remove.png" alt="rootmydot"> </h5>           <br>'; echo '<a class="io" href="signin.php">LOGUOT</a>'; }else{}  ?>
    </nav>
    
    <main class="container">

        <div class="bg-body-tertiary p-5 rounded">
            <h1>Create table</h1>

            <a class="btn btn-lg btn-primary" href="table/table.php?token=<?= $token ?>" role="button">click it</a>
        </div>
           <div class="bg-body-tertiary p-5 rounded">
            
            <h1>HISTORY-YOU-table</h1>

            <a class="btn btn-lg btn-primary" href="history.php?token=<?= $token ?>" role="button">HISTORY</a>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
















































    
</body>

</html>