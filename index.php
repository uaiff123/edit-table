
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once('API/connect.php');

if (empty($_SESSION['id'])) {
    header("Location: signin.php"); 
    exit();
}
$token = bin2hex(random_bytes(25));
?>


<?php
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // โหลดข้อมูลล่าสุดจาก DB หรือใช้ข้อมูล POST
} else {
    // เติม 5 แถวว่างไว้เลย
    $workData = [];
    for ($i = 0; $i < 5; $i++) {
        $workData[] = ['work_date' => '', 'detail' => '', 'time_start' => '', 'time_end' => ''];
    }
}

?>
<?php
// ฟังก์ชันดึงข้อมูลจาก DB จริงๆ (ยังเก็บข้อมูลใน DB ตามปกติ)
function getWorkDataFromDB() {
    // คืนค่าเป็น array ว่าง ไม่มีข้อมูล
    return [];
}


?>



<!DOCTYPE html>
<html lang="en" data-bs-theme="auto">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Astro v5.7.10">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="icon" href="img/logodatary.png" type="image/jpeg" sizes="16x16">

    <title>Home Datary</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="mycss/navbar-static.css">
    <style>
        body {
            margin: 0;
            font-family: sans-serif;
        }

        .spacer {
            flex: 1;
        }

        /* Navbar ปุ่มด้านบน */
        .navbar {
            background-color: #222;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .menu-button {
            background-color: rgb(31, 30, 30);
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
        }

        .side-menu h2 {
            margin-top: 0;
        }

        .side-menu ul {
            list-style: none;
            padding: 0;
        }

        .side-menu ul li {
            margin: 15px 0;
        }

        .side-menu a {
            color: white;
            text-decoration: none;
        }

        nav {
            background-color: rgb(0, 117, 252);
            display: inline-flex;
            width: 100%;
            height: 70px;

        }

        .container {
            background-image: url(https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRSwJMpWUVGxQvGzK6KqKGoHSMqu_yETYTmZw&s);
        }

        h5 {
            color: rgb(255, 255, 255);
        }

        h5 img {
            margin-left: -20px;
        }

        .side-menu {
            position: fixed;
            top: 0;
            left: -250px;
            width: 250px;
            height: 100%;
            background: #131212;
            color: white;
            padding: 20px;
            transition: left 0.3s ease;
            z-index: 1000;
        }

        .side-menu.active {
            left: 0;
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
            font-size: 2.5rem;
            margin-top: 10px;
        }



        .table td textarea {
            width: 100%;
            box-sizing: border-box;
            height: 100%;
            /* หรือกำหนดความสูงขั้นต่ำก็ได้ */
        }
    </style>
</head>

<body>

    <nav class="nav-new">
        <h2>Datary <img  style="width:50px; height:50px;" src="img/logodatary.png" alt="11"></h2>
        <div class="spacer"></div>

        <button class="menu-button" id="menuButton" style=" align-items:end;" onclick="toggleMenu()">☰ เมนู</button>
        <div class="side-menu" id="sideMenu">

            <h2>Datary</h2>
            <br>
            <ul>
                <li>
                    <?php
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
    echo " <h5>Name &nbsp:  &nbsp  " . htmlspecialchars($name);
    echo ' <h5>online <img style="width: 60px; height: 35px;" src="img/remove.png" alt="rootmydot"> </h5>'; 
    echo '<a class="io" style="color: red;" href="history.php"><br>ประวัติการเพิ่มงาน<br>(cick)</a>';
    
} else {
    echo "ไม่พบชื่อผู้ใช้";
    echo ' <h5> you dont have accout </h5>'; 
}
$stmt->close();
?>
                    </h5>
                </li>
                <li>
                    <?php if (isset($_SESSION['id'])) {
                    echo '<a class="io" style=" position: fixed; bottom: 30px; " href="logout.php">LOGUOT</a>';}else{}  ?>
                </li>
            </ul>
        </div>
        </div>



    </nav>
    <br><br><br><br><br><br>









<?php
$showSuccess = false;  // ตั้งเป็น false ก่อนเลย

if (isset($_GET['success']) && $_GET['success'] == 1) {
    $showSuccess = true;

    // ทำให้ URL ไม่มี success=1 อีกต่อไป
    echo "<script>
        if (window.history.replaceState) {
            const url = new URL(window.location.href);
            url.searchParams.delete('success');
            window.history.replaceState({}, document.title, url.toString());
        }
    </script>";
}
?>



<?php if ($showSuccess): ?>
    <div id="successAlert" class="alert alert-success" style="text-align: center; margin-top: 20px;" role="alert">
        ✅ บันทึกข้อมูลสำเร็จแล้ว!
    </div>
<?php endif; ?>


<script>
    // ให้แสดง 5 วินาที แล้วจางหาย
    setTimeout(function () {
        const successBox = document.getElementById('successAlert');
        const errorBox = document.getElementById('errorAlert');

        if (successBox) {
            successBox.style.transition = "opacity 1s";
            successBox.style.opacity = 0;
            setTimeout(() => successBox.remove(), 1000);
        }

        if (errorBox) {
            errorBox.style.transition = "opacity 1s";
            errorBox.style.opacity = 0;
            setTimeout(() => errorBox.remove(), 1000);
        }
    }, 1000); // 5000ms = 5 วินาที
</script>




    <div class="container mt-4">
        <form method="post" action="API/save_work.php" onsubmit="return validateBeforeSubmit();">
            <br><br>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>วันที่</th>
                        <th>รายละเอียด</th>
                        <th>เวลาเริ่ม</th>
                        <th>เวลาเลิก</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($workData as $index => $row): ?>
                    <tr>
                        <td>
                            <input type="date" name="work_date[]" class="form-control"
                                value="<?= htmlspecialchars($row['work_date']) ?>">
                        </td>
                        <td>
                            <textarea name="detail[]"
                                class="form-control"><?= htmlspecialchars($row['detail']) ?></textarea>
                        </td>
                        <td>
                            <input type="time" name="time_start[]" class="form-control"
                                value="<?= htmlspecialchars($row['time_start']) ?>">
                        </td>
                        <td>
                            <input type="time" name="time_end[]" class="form-control"
                                value="<?= htmlspecialchars($row['time_end']) ?>">
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <br><br>
            <button style="padding: 20px 20px;" type="submit" class="btn btn-primary">💾 บันทึก</button>
            <br><br>
        </form>
    </div>

    <script>
        function validateBeforeSubmit() {
            const dates = document.getElementsByName('work_date[]');
            const details = document.getElementsByName('detail[]');
            const starts = document.getElementsByName('time_start[]');
            const ends = document.getElementsByName('time_end[]');


            let foundOneComplete = false;

            for (let i = 0; i < dates.length; i++) {
                const d = dates[i].value.trim();
                const t = details[i].value.trim();
                const s = starts[i].value.trim();
                const e = ends[i].value.trim();

                if (d && t && s && e) {
                    foundOneComplete = true; // มีแถวที่ครบแล้ว
                } else {
                    // ถ้าไม่ครบทั้ง 4 ช่อง แถวนี้จะไม่ถูกส่ง
                    dates[i].name = '';
                    details[i].name = '';
                    starts[i].name = '';
                    ends[i].name = '';
                }
            }

            if (!foundOneComplete) {
                alert('อย่างน้อยต้องกรอกข้อมูลให้ครบ 1 แถว');
                return false;
            }

            return true;
        }


    </script>
    <script>
        const sideMenu = document.getElementById("sideMenu");
        const menuButton = document.getElementById("menuButton");

        // กดปุ่มเมนู → toggle เปิด/ปิด
        menuButton.addEventListener("click", function (e) {
            e.stopPropagation(); // กันไม่ให้ document ถูก trigger ทันที
            sideMenu.classList.toggle("active");
        });

        // คลิกที่เมนู → ไม่ปิดเมนู
        sideMenu.addEventListener("click", function (e) {
            e.stopPropagation(); // กันไม่ให้ trigger ตัว document เช่นกัน
        });

        // คลิกที่อื่นของเว็บ → ปิดเมนู
        document.addEventListener("click", function () {
            sideMenu.classList.remove("active");
        });
    </script>




    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</body>

</html>