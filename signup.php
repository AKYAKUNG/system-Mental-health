<?php
require_once 'db/db.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>MentalHealth - สมัครสมาชิก</title>
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
  100%{transform: rotate(1turn)}
}

.register-card {
    max-width: 400px;
    margin: 80px auto;
    /* เพิ่มจาก 40px → 80px */
    background: #fff;
    border-radius: 16px;
    border: 1px solid #f8c6d8;
    padding: 20px;
}

@media (max-width: 576px) {
    .register-card {
        margin-top: 100px;
        /* เว้นเยอะตอนมือถือ */
    }
}

@media (min-width: 577px) {
    .register-card {
        margin-top: 60px;
        /* คอมเว้นพอดี */
    }
}

.textloader {
    color: white;
    margin-top: 20px;
}

h2 {
    color: #e75480;
    /* ชมพูเข้ม */
    font-weight: 600;
}

.form-control {
    border-radius: 10px;
    border: 1px solid #f3b9d3;
}

.form-control:focus {
    border-color: #e75480;
    box-shadow: 0 0 6px rgba(231, 84, 128, 0.3);
}

.btn-pink {
    background-color: #e75480;
    color: #fff;
    border-radius: 12px;
    padding: 10px;
    transition: 0.3s;
    border: none;
}

.btn-pink:hover {
    background-color: #d63d6c;
}

.text-pink {
    color: #e75480 !important;
    text-decoration: none;
}

.text-pink:hover {
    text-decoration: underline;
}

#btn-submit:hover {
    background-color: #ff00e6 !important;
    color: white !important;
}
</style>
<body>
<!-- Loading Screen -->
<div id="loader">
    <div class="loader"></div>
    <p id="textloader" class="textloader"></p>
</div>

<div id="content" style="display:none;">
  <div class="container d-flex justify-content-center">
    <div class="register-card shadow p-5 rounded mt-5" style="width:100%; max-width:500px; background:#fff;">
      <div class="text-center mb-4">
        <img class="imglogo d-block mx-auto mb-3" src="img/Logo.png" alt="Logo" style="width:100px;">
        <h2 class="mb-3">สมัครสมาชิก</h2>
      </div>

      <form action="db/signup_db.php" method="post">
        <!-- alert -->
        <?php if(isset($_SESSION['error'])){ ?>
          <div class="alert alert-danger text-center"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
        <?php } ?>
        <?php if(isset($_SESSION['success'])){ ?>
          <div class="alert alert-success text-center"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php } ?>
        <?php if(isset($_SESSION['warning'])){ ?>
          <div class="alert alert-warning text-center"><?php echo $_SESSION['warning']; unset($_SESSION['warning']); ?></div>
        <?php } ?>

        <div class="mb-3">
          <label for="username" class="form-label">ชื่อ-สกุล</label>
          <input type="text" class="form-control" name="username" id="username" placeholder="กรอกชื่อ-สกุล" required>
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">อีเมล</label>
          <input type="email" class="form-control" name="email" id="email" placeholder="กรอกอีเมล" required>
        </div>

        <div class="mb-3">
          <label for="tel" class="form-label">เบอร์โทรศัพท์</label>
          <input type="tel" class="form-control" name="tel" id="tel" placeholder="กรอกเบอร์โทรศัพท์" required>
        </div>

        <div class="mb-3">
          <label for="password" class="form-label">รหัสผ่าน</label>
          <input type="password" class="form-control" name="password" id="password" placeholder="กรอกรหัสผ่าน" required>
        </div>

        <div class="mb-3">
          <label for="confirmPassword" class="form-label">ยืนยันรหัสผ่าน</label>
          <input type="password" class="form-control" name="c_password" id="confirmPassword" placeholder="ยืนยันรหัสผ่าน" required>
        </div>

        <button type="submit" name="signup" class="btn btn-pink w-100 py-2">สมัครสมาชิก</button>
        <p class="mt-3 text-center">มีบัญชีแล้ว? <a href="signin.php" class="text-pink">เข้าสู่ระบบ</a></p>
      </form>
    </div>
  </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Loader
  window.addEventListener("load", function() {
    setTimeout(function() {
      document.getElementById("loader").style.display = "none";
      document.getElementById("content").style.display = "block";
    },2000);
  });

  // Typewriter
  document.addEventListener("DOMContentLoaded", function() {
    const text = "กำลังโหลด...";
    const speed = 100;
    let i = 0;
    const textElement = document.getElementById("textloader");
    function typeWriter() {
      if(i<text.length){
        textElement.innerHTML += text.charAt(i);
        i++;
        setTimeout(typeWriter,speed);
      }
    }
    typeWriter();
  });
</script>
</body>
</html>
