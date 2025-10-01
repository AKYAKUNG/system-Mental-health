<?php
require_once 'db/db.php';
session_start();
if (!isset($_SESSION['user_login'])) {
    $_SESSION['warning'] = '<b>กรุณาเข้าสู่ระบบ</b>';
    header('Location: signin.php');
    exit();
}
    $user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MentalHealth - สมัครสมาชิก</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css">
</head>
<style>
    body {
        background: #ffe6f0;
        font-family: "Noto Sans Thai", sans-serif;
        min-height: 100vh;
    }

    .survey-card {
        max-width: 600px;
        margin: 50px auto;
        background: #fff;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
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
    <div class="container">
        <?php
        if (isset($_SESSION['user_login'])) {
            $user_id = $_SESSION['user_login'];
            $stmt = $conn->query("SELECT * FROM users WHERE id = $user_id");
            $stmt->execute();
            $username = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        ?>
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <h3 class="text-center"> สวัสดีคุณ <?php echo $username['username'] ?></h3>
                </div>
            </div>
        </div>
        <div class="survey-card">
            <h2 class="text-center mb-4">แบบสอบถามความพึงพอใจ</h2>

            <form action="db/score.php" method="post">
                <?php if (isset($_SESSION['error'])) { ?>
                    <div class="alert alert-danger text-center">
                        <?php echo $_SESSION['error'];
                        unset($_SESSION['error']); ?>
                    </div>
                <?php } ?>

                <?php if (isset($_SESSION['success'])) { ?>
                    <div class="alert alert-success text-center">
                        <?php echo $_SESSION['success'];
                        unset($_SESSION['success']); ?>
                    </div>
                <?php } ?>
                <div class="mb-3">
                    <label class="form-label">1. คุณพอใจกับบริการของเราแค่ไหน?</label>
                    <div class="rating">
                        <input type="radio" id="q1-5" name="q1" value="5"><label for="q1-5">5</label>
                        <input type="radio" id="q1-4" name="q1" value="4"><label for="q1-4">4</label>
                        <input type="radio" id="q1-3" name="q1" value="3"><label for="q1-3">3</label>
                        <input type="radio" id="q1-2" name="q1" value="2"><label for="q1-2">2</label>
                        <input type="radio" id="q1-1" name="q1" value="1"><label for="q1-1">1</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">2. คุณพอใจกับคุณภาพสินค้าแค่ไหน?</label>
                    <div class="rating">
                        <input type="radio" id="q2-5" name="q2" value="5"><label for="q2-5">5</label>
                        <input type="radio" id="q2-4" name="q2" value="4"><label for="q2-4">4</label>
                        <input type="radio" id="q2-3" name="q2" value="3"><label for="q2-3">3</label>
                        <input type="radio" id="q2-2" name="q2" value="2"><label for="q2-2">2</label>
                        <input type="radio" id="q2-1" name="q2" value="1"><label for="q2-1">1</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">3. คุณพอใจกับคุณภาพสินค้าแค่ไหน?</label>
                    <div class="rating">
                        <input type="radio" id="q3-5" name="q3" value="5"><label for="q3-5">5</label>
                        <input type="radio" id="q3-4" name="q3" value="4"><label for="q3-4">4</label>
                        <input type="radio" id="q3-3" name="q3" value="3"><label for="q3-3">3</label>
                        <input type="radio" id="q3-2" name="q3" value="2"><label for="q3-2">2</label>
                        <input type="radio" id="q3-1" name="q3" value="1"><label for="q3-1">1</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">4. คุณพอใจกับคุณภาพสินค้าแค่ไหน?</label>
                    <div class="rating">
                        <input type="radio" id="q4-5" name="q4" value="5"><label for="q4-5">5</label>
                        <input type="radio" id="q4-4" name="q4" value="4"><label for="q4-4">4</label>
                        <input type="radio" id="q4-3" name="q4" value="3"><label for="q4-3">3</label>
                        <input type="radio" id="q4-2" name="q4" value="2"><label for="q4-2">2</label>
                        <input type="radio" id="q4-1" name="q4" value="1"><label for="q4-1">1</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">5. คุณพอใจกับคุณภาพสินค้าแค่ไหน?</label>
                    <div class="rating">
                        <input type="radio" id="q5-5" name="q5" value="5"><label for="q5-5">5</label>
                        <input type="radio" id="q5-4" name="q5" value="4"><label for="q5-4">4</label>
                        <input type="radio" id="q5-3" name="q5" value="3"><label for="q5-3">3</label>
                        <input type="radio" id="q5-2" name="q5" value="2"><label for="q5-2">2</label>
                        <input type="radio" id="q5-1" name="q5" value="1"><label for="q5-1">1</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">6. คุณพอใจกับคุณภาพสินค้าแค่ไหน?</label>
                    <div class="rating">
                        <input type="radio" id="q6-5" name="q6" value="5"><label for="q6-5">5</label>
                        <input type="radio" id="q6-4" name="q6" value="4"><label for="q6-4">4</label>
                        <input type="radio" id="q6-3" name="q6" value="3"><label for="q6-3">3</label>
                        <input type="radio" id="q6-2" name="q6" value="2"><label for="q6-2">2</label>
                        <input type="radio" id="q6-1" name="q6" value="1"><label for="q6-1">1</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">7. คุณพอใจกับคุณภาพสินค้าแค่ไหน?</label>
                    <div class="rating">
                        <input type="radio" id="q7-5" name="q7" value="5"><label for="q7-5">5</label>
                        <input type="radio" id="q7-4" name="q7" value="4"><label for="q7-4">4</label>
                        <input type="radio" id="q7-3" name="q7" value="3"><label for="q7-3">3</label>
                        <input type="radio" id="q7-2" name="q7" value="2"><label for="q7-2">2</label>
                        <input type="radio" id="q7-1" name="q7" value="1"><label for="q7-1">1</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">8. คุณพอใจกับคุณภาพสินค้าแค่ไหน?</label>
                    <div class="rating">
                        <input type="radio" id="q8-5" name="q8" value="5"><label for="q8-5">5</label>
                        <input type="radio" id="q8-4" name="q8" value="4"><label for="q8-4">4</label>
                        <input type="radio" id="q8-3" name="q8" value="3"><label for="q8-3">3</label>
                        <input type="radio" id="q8-2" name="q8" value="2"><label for="q8-2">2</label>
                        <input type="radio" id="q8-1" name="q8" value="1"><label for="q8-1">1</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">9. คุณพอใจกับคุณภาพสินค้าแค่ไหน?</label>
                    <div class="rating">
                        <input type="radio" id="q9-5" name="q9" value="5"><label for="q9-5">5</label>
                        <input type="radio" id="q9-4" name="q9" value="4"><label for="q9-4">4</label>
                        <input type="radio" id="q9-3" name="q9" value="3"><label for="q9-3">3</label>
                        <input type="radio" id="q9-2" name="q9" value="2"><label for="q9-2">2</label>
                        <input type="radio" id="q9-1" name="q9" value="1"><label for="q9-1">1</label>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">10. คุณพอใจกับคุณภาพสินค้าแค่ไหน?</label>
                    <div class="rating">
                        <input type="radio" id="q10-5" name="q10" value="5"><label for="q10-5">5</label>
                        <input type="radio" id="q10-4" name="q10" value="4"><label for="q10-4">4</label>
                        <input type="radio" id="q10-3" name="q10" value="3"><label for="q10-3">3</label>
                        <input type="radio" id="q10-2" name="q10" value="2"><label for="q10-2">2</label>
                        <input type="radio" id="q10-1" name="q10" value="1"><label for="q10-1">1</label>
                    </div>
                </div>

                <button type="submit" name="answer" class="btn btn-primary w-100 mt-3">ส่งคำตอบ</button>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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