<?php
require_once 'db/db.php';
session_start();
if (!isset($_SESSION['user_id'])) {
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
    <title>MentalHealth - แบบสอบวัดระดับความเครียด</title>
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

    .form-container {
        max-width: 900px;
        margin: 40px auto;
        background: #ffffffff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
    }
</style>

<body>
    <div class="container">
        <form action="db/score.php" method="post" class="form-container">

            <?php if (isset($_SESSION['error'])) { ?>
                <div class="alert alert-danger text-center py-2">
                    <?php echo $_SESSION['error'];
                    unset($_SESSION['error']); ?>
                </div>
            <?php } ?>

            <?php if (isset($_SESSION['success'])) { ?>
                <div class="alert alert-success text-center py-2">
                    <?php echo $_SESSION['success'];
                    unset($_SESSION['success']); ?>
                </div>
            <?php } ?>
            <div class="mb-3">
                <h1 class="text-center mt-5 mb-4 fw-bold">แบบสอบถามวัดระดับความเครียด</h1>
                <label class="form-label">1. คุณมีปัญหาในการนอน (นอนไม่หลับ/นอนน้อยกว่าปกติ)</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="q1" id="q1-0" value="0">
                        <label class="form-check-label" for="q1-0">แทบไม่เป็นเลย</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="q1" id="q1-1" value="1">
                        <label class="form-check-label" for="q1-1">เป็นบางวัน (0-7 วัน)</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="q1" id="q1-2" value="2">
                        <label class="form-check-label" for="q1-2">เป็นบ่อยๆ</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="q1" id="q1-3" value="3">
                        <label class="form-check-label" for="q1-3">เป็นแทบทุกวัน</label>
                    </div>
                </div>
            </div>

            <!-- ข้อ 2 -->
            <div class="mb-3">
                <label class="form-label">2. คุณมีสมาธิน้อยลง หลงลืมง่าย</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="q2" id="q2-0" value="0">
                        <label class="form-check-label" for="q2-0">แทบไม่เป็นเลย</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="q2" id="q2-1" value="1">
                        <label class="form-check-label" for="q2-1">เป็นบางวัน (0-7 วัน)</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="q2" id="q2-2" value="2">
                        <label class="form-check-label" for="q2-2">เป็นบ่อยๆ</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="q2" id="q2-3" value="3">
                        <label class="form-check-label" for="q2-3">เป็นแทบทุกวัน</label>
                    </div>
                </div>
            </div>

            <!-- ข้อ 3 -->
            <div class="mb-3">
                <label class="form-label">3. คุณหงุดหงิดง่าย ว้าวุ่นใจ</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="q3" id="q3-0" value="0">
                        <label class="form-check-label" for="q3-0">แทบไม่เป็นเลย</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="q3" id="q3-1" value="1">
                        <label class="form-check-label" for="q3-1">เป็นบางวัน (0-7 วัน)</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="q3" id="q3-2" value="2">
                        <label class="form-check-label" for="q3-2">เป็นบ่อยๆ</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="q3" id="q3-3" value="3">
                        <label class="form-check-label" for="q3-3">เป็นแทบทุกวัน</label>
                    </div>
                </div>
            </div>


            <!-- ข้อ 4 -->
            <div class="mb-3">
                <label class="form-label">4. คุณรู้สึกเบื่อหน่ายเซ็ง</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="q4" id="q4-0" value="0">
                        <label class="form-check-label" for="q4-0">แทบไม่เป็นเลย</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="q4" id="q4-1" value="1">
                        <label class="form-check-label" for="q4-1">เป็นบางวัน (0-7 วัน)</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="q4" id="q4-2" value="2">
                        <label class="form-check-label" for="q4-2">เป็นบ่อยๆ</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="q4" id="q4-3" value="3">
                        <label class="form-check-label" for="q4-3">เป็นแทบทุกวัน</label>
                    </div>
                </div>
            </div>

            <!-- ข้อ 5 -->
            <div class="mb-3">
                <label class="form-label">5. คุณไม่อยากพบปะผู้คน (เก็บตัว)</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="q5" id="q5-0" value="0">
                        <label class="form-check-label" for="q5-0">แทบไม่เป็นเลย</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="q5" id="q5-1" value="1">
                        <label class="form-check-label" for="q5-1">เป็นบางวัน (0-7 วัน)</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="q5" id="q5-2" value="2">
                        <label class="form-check-label" for="q5-2">เป็นบ่อยๆ</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="q5" id="q5-3" value="3">
                        <label class="form-check-label" for="q5-3">เป็นแทบทุกวัน</label>
                    </div>
                </div>
            </div>

            <!-- ข้อ 6 -->
            <div class="mb-3">
                <label class="form-label">6. คุณปวดหัวข้างเดียวหรือปวดหัวบริเวณขมับทั้งสองข้าง</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="q6" id="q6-0" value="0">
                        <label class="form-check-label" for="q6-0">แทบไม่เป็นเลย</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="q6" id="q6-1" value="1">
                        <label class="form-check-label" for="q6-1">เป็นบางวัน (0-7 วัน)</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="q6" id="q6-2" value="2">
                        <label class="form-check-label" for="q6-2">เป็นบ่อยๆ</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="q6" id="q6-3" value="3">
                        <label class="form-check-label" for="q6-3">เป็นแทบทุกวัน</label>
                    </div>
                </div>
            </div>
            <!-- ข้อ 7 -->
            <div class="mb-3">
                <label class="form-label">7. คุณรู้สึกเพลียจนไม่มีแรงทำอะไร</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="q7" id="q7-0" value="0">
                        <label class="form-check-label" for="q7-0">แทบไม่เป็นเลย</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="q7" id="q7-1" value="1">
                        <label class="form-check-label" for="q7-1">เป็นบางวัน (0-7 วัน)</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="q7" id="q7-2" value="2">
                        <label class="form-check-label" for="q7-2">เป็นบ่อยๆ</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="q7" id="q7-3" value="3">
                        <label class="form-check-label" for="q7-3">เป็นแทบทุกวัน</label>
                    </div>
                </div>
            </div>

            <!-- ข้อ 8 -->
            <div class="mb-3">
                <label class="form-label">8. คุณกระวนกระวายอยู่ตลอดเวลา</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="q8" id="q8-0" value="0">
                        <label class="form-check-label" for="q8-0">แทบไม่เป็นเลย</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="q8" id="q8-1" value="1">
                        <label class="form-check-label" for="q8-1">เป็นบางวัน (0-7 วัน)</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="q8" id="q8-2" value="2">
                        <label class="form-check-label" for="q8-2">เป็นบ่อยๆ</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="q8" id="q8-3" value="3">
                        <label class="form-check-label" for="q8-3">เป็นแทบทุกวัน</label>
                    </div>
                </div>
            </div>

            <!-- ข้อ 9 -->
            <div class="mb-3">
                <label class="form-label">9. คุณหมดหวังในชีวิต</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="q9" id="q9-0" value="0">
                        <label class="form-check-label" for="q9-0">แทบไม่เป็นเลย</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="q9" id="q9-1" value="1">
                        <label class="form-check-label" for="q9-1">เป็นบางวัน (0-7 วัน)</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="q9" id="q9-2" value="2">
                        <label class="form-check-label" for="q9-2">เป็นบ่อยๆ</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="q9" id="q9-3" value="3">
                        <label class="form-check-label" for="q9-3">เป็นแทบทุกวัน</label>
                    </div>
                </div>
            </div>

            <!-- ข้อ 10 -->
            <div class="mb-3">
                <label class="form-label">10. คุณปวดหรือเกร็งกล้ามเนื้อบริเวณท้ายทอย หลัง หรือไหล่</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="q10" id="q10-0" value="0">
                        <label class="form-check-label" for="q10-0">แทบไม่เป็นเลย</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="q10" id="q10-1" value="1">
                        <label class="form-check-label" for="q10-1">เป็นบางวัน (0-7 วัน)</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="q10" id="q10-2" value="2">
                        <label class="form-check-label" for="q10-2">เป็นบ่อยๆ</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="q10" id="q10-3" value="3">
                        <label class="form-check-label" for="q10-3">เป็นแทบทุกวัน</label>
                    </div>
                </div>
            </div>

            <button type="submit" name="answer" class="btn btn-primary w-100 py-2 fw-bold">ส่งคำตอบ</button>
            <a href="user.php" class="d-block text-center mt-3 text-decoration-none text-muted">ยกเลิก</a>
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