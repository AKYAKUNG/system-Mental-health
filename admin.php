<?php
require_once 'db/db.php';
session_start();

// ตรวจสอบล็อกอิน
if (!isset($_SESSION['user_id'])) {
    $_SESSION['warning'] = '<b>กรุณาเข้าสู่ระบบ</b>';
    header('Location: signin.php');
    exit();
}
$user_id = $_SESSION['user_id'];

// ดึงเฉพาะผู้ใช้ role = 'user'
$stmtUsers = $conn->prepare("SELECT * FROM users WHERE urole = :urole");
$role = 'user';
$stmtUsers->bindParam(':urole', $role, PDO::PARAM_STR);
$stmtUsers->execute();
$users = $stmtUsers->fetchAll(PDO::FETCH_ASSOC);

// ดึงข้อมูลของผู้ใช้ที่ login ตาม id
$stmtCurrent = $conn->prepare("SELECT * FROM users WHERE id = :id");
$stmtCurrent->bindParam(':id', $user_id, PDO::PARAM_INT);
$stmtCurrent->execute();
$currentUser = $stmtCurrent->fetch(PDO::FETCH_ASSOC);


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MentalHealth - คุณผู้ดูแล <?php echo htmlspecialchars($currentUser['username']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@100..900&display=swap');

    body {
        font-family: "Noto Sans Thai", sans-serif;
        font-optical-sizing: auto;
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        /* จัดกลางจอ จะเห็น gradient เคลื่อนได้ชัด */
        background: linear-gradient(-45deg, #ffe6f0, #ffc2e2, #ff9ac8, #ffb3d9);
        background-size: 400% 400%;
        animation: gradientBG 12s ease infinite;
    }

    @keyframes gradientBG {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }

    #loader {
        position: fixed;
        z-index: 9999;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(-45deg, #ffe6f0, #ffd6ec, #ffc2e2, #ffb3d9);
        background-size: 400% 400%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        animation: gradientBG 12s ease infinite;
        font-family: "Noto Sans Thai", sans-serif;
    }

    /* HTML: <div class="loader"></div> */
    .loader {
        width: 50px;
        aspect-ratio: 1;
        display: grid;
        border: 4px solid #0000;
        border-radius: 50%;
        border-color: #ffffff #0000;
        animation: l16 1s infinite linear;
    }

    .loader::before,
    .loader::after {
        content: "";
        grid-area: 1/1;
        margin: 2px;
        border: inherit;
        border-radius: 50%;
    }

    .loader::before {
        border-color: #ff00e6 #0000;
        animation: inherit;
        animation-duration: .5s;
        animation-direction: reverse;
    }

    .loader::after {
        margin: 8px;
    }

    @keyframes l16 {
        100% {
            transform: rotate(1turn)
        }
    }

    .textloader {
        color: white;
        margin-top: 20px;
    }

    .row-box {
        border-radius: 20px;
    }
</style>

<body>
    <!-- Loading Screen -->
    <div id="loader">
        <div class="loader"></div>
        <p id="textloader" class="textloader"></p>
    </div>
    <div class="container mt-4">
        <!-- ส่วนโปรไฟล์ผู้ดูแล -->
        <div class="row bg-white row-box mb-4 p-3">
            <div class="row align-items-center">
                <div class="col-sm-5 text-center">
                    <img class="img-fluid rounded-circle p-1 w-75" src="img/Logo.png" alt="icon user">
                </div>
                <div class="col-sm-7 text-start align-self-start">
                    <h3>สวัสดีคุณผู้ดูแล</h3>
                    <h3><?php echo htmlspecialchars($currentUser['username']); ?> 👤</h3>
                    <p class="mb-0">ยินดีต้อนรับเข้าสู่ระบบจัดการของคุณ</p>
                    <p class="mb-0">📌 สิทธิ์: ผู้ดูแลระบบ</p>
                    <p class="mb-0">📌 วันที่เข้าสู่ระบบล่าสุด: <?php echo date('d/m/Y'); ?></p>
                    <p class="mb-0">📌 สิทธิ์ผู้ดูแลระบบ มีทั้งหมด: 5 คน ได้แก่</p>
                    <ul class="list-unstyled">
                        <li> 1.นาย ธนากร ระแสนพรหม 6640400930</li>
                        <li> 2.นางสาว พรนภา มีสติ 6640401631</li>
                        <li> 3.นางสาว ปัทมา กันยาพันธ์ 6640402951</li>
                        <li> 4.นางสาว ณีรนุช จิตมาตย์ 6640403272</li>
                        <li> 5.นางสาว ปภาพินท์ หนูเงิน 6640403413</li>
                        <li> 6.นางสาว หทัยภัทร ทูลแก้ว 6640403678</li>
                    </ul>
                    <a class="btn btn-danger mt-2" href="db/logout.php">ออกจากระบบ</a>
                </div>
            </div>
        </div>

        <!-- ส่วนตารางผู้ใช้งาน -->
        <div class="row bg-white row-box p-3">
            <div class="col-12">
                <h2 class="text-center">ตารางข้อมูลผู้ทำแบบสอบถาม</h2>
                <div class="table-responsive">
                    <table class="table table-bordered text-center" style="min-width: 1200px;">
                        <thead class="table-dark align-middle">
                            <tr>
                                <th>ชื่อผู้ใช้งาน</th>
                                <th>Email - ผู้ใช้งาน</th>
                                <th>เบอร์โทรศัพท์ - ผู้ใช้งาน</th>
                                <th>ตำแหน่ง - ผู้ใช้งาน</th>
                                <th>วันเวลาสมัคร - ผู้ใช้งาน</th>
                                <th>ตรวจสอบข้อมูล - ผู้ใช้งาน</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (count($users) > 0) {
                                foreach ($users as $row) {
                                    echo "<tr class='align-middle'>";
                                    echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
                                    echo "<td class='text-truncate' style='max-width:200px;'>" . htmlspecialchars($row["email"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["tel"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["urole"]) . "</td>";
                                    echo "<td>" . htmlspecialchars($row["created_at"]) . "</td>";
                                    echo '<td><a class="btn btn-success btn-sm" href="user_viwe.php?id=' . $row['id'] . '">ดูข้อมูลผู้ใช้งาน</a></td>';
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='6'>ไม่มีข้อมูล</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        window.addEventListener("load", function() {
            setTimeout(function() {
                // ซ่อนหน้าโหลด
                document.getElementById("loader").style.display = "none";
                // แสดงเนื้อหาเว็บจริง
                document.getElementById("content").style.display = "block";
            }, 2000); // 2000ms = 2 วินาที
        });
        document.addEventListener("DOMContentLoaded", function() {
            const text = "กำลังโหลด..."; // ข้อความที่จะพิมพ์
            const speed = 100; // ความเร็ว (ms) ต่อ 1 ตัวอักษร
            let i = 0;
            const textElement = document.getElementById("textloader");

            function typeWriter() {
                if (i < text.length) {
                    textElement.innerHTML += text.charAt(i);
                    i++;
                    setTimeout(typeWriter, speed);
                }
            }

            typeWriter();
        });
    </script>
</body>

</html>