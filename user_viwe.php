<?php
require_once 'db/db.php';
session_start();

// ตรวจสอบล็อกอิน
if (!isset($_SESSION['user_id'])) {
    $_SESSION['warning'] = 'กรุณาเข้าสู่ระบบ';
    header('Location: signin.php');
    exit();
}

// ดึง id ของผู้ใช้จาก GET
$user_id = $_GET['id'] ?? 0;

// ดึงข้อมูล user
$stmtUser = $conn->prepare("SELECT username, email, tel FROM users WHERE id = :user_id");
$stmtUser->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmtUser->execute();
$user = $stmtUser->fetch(PDO::FETCH_ASSOC);

// ดึงข้อมูล score ของ user ที่ล็อกอิน (ถ้ามี)
$stmtScore = $conn->prepare("
    SELECT total_score, created_at
    FROM score
    WHERE users_id = :user_id
    ORDER BY created_at DESC
    LIMIT 1
");
$stmtScore = $conn->prepare("
    SELECT total_score, created_at
    FROM score
    WHERE users_id = :user_id
    ORDER BY created_at DESC
    LIMIT 1
");
$stmtScore->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmtScore->execute();
$score = $stmtScore->fetch(PDO::FETCH_ASSOC);

if ($score) {
    $totalScore = (int)$score['total_score'];

    if ($totalScore <= 4) {
        $stressLevel = "เครียดเล็กน้อย ไม่เครียด";
        $chartColor = 'rgba(0, 200, 0, 1)'; // เขียว
        $chartBg = 'rgba(0, 200, 0, 0.2)';
        $stressDesc = "ควรดูแลสุขภาพจิตให้ ดีแบบนี้ไปตลอด";
    } elseif ($totalScore <= 7) {
        $stressLevel = "เครียดปานกลาง";
        $chartColor = 'rgba(255, 165, 0, 1)'; // ส้ม
        $chartBg = 'rgba(255, 165, 0, 0.2)';
        $stressDesc = "ควรฝึกผ่อนคลาดความเครียดและจัดการสาเหตุของความเครียดให้คลี่คลาย";
    } elseif ($totalScore <= 9) {
        $stressLevel = "เครียดมาก";
        $chartColor = 'rgba(200, 0, 0, 1)'; // แดง
        $chartBg = 'rgba(255, 99, 71, 0.2)';
        $stressDesc = "ควรได้รับการประเมิน/ค้นหาความไม่สบายทางจิตเวชจากบุคลากรสาธารณสุข";
    } else {
        $stressLevel = "เครียดมากที่สุด";
        $chartColor = 'rgba(100, 0, 200, 1)'; // ม่วง
        $chartBg = 'rgba(200, 0, 0, 0.2)';
        $stressDesc = '<span style="color:red; font-weight:bold;">ต้องได้รับการประเมิน/ค้นหาความไม่สบายทางจิตเวชจากบุคลากรสาธารณสุข</span>';
    }
}

// ดึงข้อมูล score ทั้งหมดของผู้ใช้ (7 วันล่าสุด)
$stmtScores = $conn->prepare("
    SELECT total_score, DATE(created_at) AS date
    FROM score
    WHERE users_id = :user_id
    ORDER BY created_at DESC
    LIMIT 7
");
$stmtScores->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmtScores->execute();
$scores = $stmtScores->fetchAll(PDO::FETCH_ASSOC);

// จัดข้อมูลสำหรับ Chart.js
$labels = [];
$data = [];

if ($scores) {
    // กลับลำดับวันที่ให้เป็นจากเก่า->ใหม่
    $scores = array_reverse($scores);
    foreach ($scores as $row) {
        $labels[] = $row['date'];
        $data[] = (int)$row['total_score'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MentalHealth - คุณ <?php echo htmlspecialchars($user['username']); ?></title>
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

    .survey-card {
        max-width: 900px;
        /* กว้างขึ้น */
        margin: 50px auto;
        /* อยู่กึ่งกลางหน้า */
        padding: 60px;
        /* เพิ่มพื้นที่รอบ ๆ */
        background-color: #f8f9fa;
        /* สีพื้นอ่อน มินิมอล */
        border-radius: 15px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .rating label {
        margin-right: 15px;
        font-size: 1.2rem;
        cursor: pointer;
    }

    .rating input {
        display: none;
    }

    .rating input:checked+label {
        color: #e75480;
        font-weight: bold;
    }
</style>

<body>
    <!-- Loading Screen -->

    <div id="loader">
        <div class="loader"></div>
        <p id="textloader" class="textloader"></p>
    </div>
    <div class="container mt-4">
        <div class="survey-card shadow-sm rounded">
            <div class="row align-items-center">
                <!-- รูปผู้ใช้ -->
                <div class="col-sm-5 text-center">
                    <img class="img-fluid rounded-circle p-1" src="img/icon.jpg" alt="icon user">
                </div>

                <!-- ข้อมูลผู้ใช้ -->
                <div class="col-sm-7">
                    <h3>คุณ <?php echo htmlspecialchars($user['username']); ?></h3>
                    <?php if (isset($_SESSION['success'])) { ?>
                        <div class="alert alert-success text-center">
                            <?php echo $_SESSION['success'];
                            unset($_SESSION['success']); ?>
                        </div>
                    <?php } ?>
                    <?php if ($score): ?>
                        <p class="mb-2">คะแนนความเครียดวันนี้: <?php echo htmlspecialchars($score['total_score']); ?></p>
                        <p>
                            ระดับความเครียด:
                            <span
                                <?php
                                if ($score['total_score'] <= 4) echo 'class="text-success fw-bold"';
                                elseif ($score['total_score'] <= 7) echo 'class="text-warning fw-bold"';
                                elseif ($score['total_score'] <= 9) echo 'class="text-danger fw-bold"';
                                else echo 'class="fw-bold" style="color: purple;"';
                                ?>>
                                <?php echo htmlspecialchars($stressLevel); ?>
                            </span>
                        </p>
                        <p style="margin-bottom: 0;">คำแนะนำ :</p>
                        <p class="text-muted" style="margin-bottom: 0;">
                            <?php echo ($stressDesc); ?>
                        </p>
                        <a class="btn mt-5 me-2" style="background-color: #ff002bff; color:white;" href="admin.php">
                            ย้อนกลับไปหน้าผู้ดูแล
                        </a>
                        <br>
                </div>
            </div>

        </div>
        <div class="container mt-5 mb-5 p-3" style="max-width:100%; background-color:white; border-radius: 15px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);">
            <h3 class="text-center">กราฟความเครียด 7 วัน</h3>
            <p class="text-center mb-0">วันที่ทำแบบสอบถามล่าสุด:</p>
            <p class="text-center mb-0"><?php echo htmlspecialchars($score['created_at']); ?></p>
            <br>
            <div style="width:100%; height:400px;">
                <canvas id="stressChart"></canvas>
            </div><br>
        <?php else: ?>
            <p class="text-danger fw-bold">สมาชิกยังไม่ได้ทำแบบสอบถาม</p>
        <?php endif; ?>
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

        const ctx = document.getElementById('stressChart').getContext('2d');
        const stressChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    label: 'คะแนนความเครียด',
                    data: <?php echo json_encode($data); ?>,
                    borderColor: '<?php echo $chartColor; ?>',
                    backgroundColor: '<?php echo $chartBg; ?>',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 5,
                    pointBackgroundColor: '<?php echo $chartColor; ?>',
                    pointBorderColor: 'white',
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        min: 0,
                        max: 30,
                        ticks: {
                            stepSize: 5
                        },
                        title: {
                            display: true,
                            text: 'คะแนน'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'วันที่'
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    },
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        });
    </script>
</body>

</html>