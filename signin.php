<?php
session_start();
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

<body>
  <!-- Loading Screen -->
  <div id="loader" >
    <div class="loader"></div>
    <p id="textloader" class="textloader"></p>
  </div>
  <!-- container -->
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5">
        <div class="register-card shadow p-4 rounded mt-5">
          <div class="text-center mb-3">
            <img class="imglogo d-block mx-auto mb-3" src="img/Logo.png" alt="Logo" style="width:80px;">
          </div>
          <h2 class="text-center mb-4">เข้าสู่ระบบ</h2>

          <form action="db/signin_db.php" method="post">
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

            <?php if (isset($_SESSION['warning'])) { ?>
              <div class="alert alert-warning text-center">
                <?php echo $_SESSION['warning'];
                unset($_SESSION['warning']); ?>
              </div>
            <?php } ?>

            <div class="mb-3">
              <label for="email" class="form-label">อีเมล</label>
              <input type="email" class="form-control" name="email" id="email" placeholder="กรอกอีเมล">
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">รหัสผ่าน</label>
              <input type="password" class="form-control" name="password" id="password" placeholder="กรอกรหัสผ่าน">
            </div>

            <button type="submit" name="signin" class="btn btn-pink w-100">เข้าสู่ระบบ</button>
            <p class="mt-3 text-center">หากยังไม่มีบัญชีสามารถ <a href="signup.php" class="text-pink">สมัครสมาชิก</a></p>
          </form>
           <a href="db/logout.php">rrr</a>
        </div>
      </div>
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