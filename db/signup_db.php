<?php
session_start();
require_once 'db.php';

if (isset($_POST['signup'])) {
    $username   = $_POST['username'];
    $email      = $_POST['email'];
    $tel        = $_POST['tel'];
    $password   = $_POST['password'];
    $c_password = $_POST['c_password'];
    $urole      = 'user';

    // Validation
    if (empty($username)) {
        $_SESSION['error'] = '<b>กรุณากรอกชื่อจริงและนามสกุลของท่าน</b>';
        header("location: ../signup.php");
        exit();
    } else if (empty($email)) {
        $_SESSION['error'] = '<b>กรุณากรอกอีเมลของท่าน</b>';
        header("location: ../signup.php");
        exit();
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['warning'] = '<b>รูปแบบอีเมลของท่านไม่ถูกต้อง</b>';
        header("location: ../signup.php");
        exit();
    } else if (empty($tel)) {
        $_SESSION['error'] = '<b>กรุณากรอกเบอร์โทรศัพท์ของท่าน</b>';
        header("location: ../signup.php");
        exit();
    } else if (strlen($tel) != 10) {
        $_SESSION['error'] = '<b>กรุณากรอกเบอร์โทรศัพท์ให้ครบ 10 ตัว</b>';
        header("location: ../signup.php");
        exit();
    } else if (empty($password)) {
        $_SESSION['error'] = '<b>กรุณาสร้างรหัสผ่านของท่าน</b>';
        header("location: ../signup.php");
        exit();
    } else if (empty($c_password)) {
        $_SESSION['error'] = '<b>กรุณายืนยันรหัสผ่านของท่าน</b>';
        header("location: ../signup.php");
        exit();
    } else if ($password != $c_password) {
        $_SESSION['error'] = '<b>รหัสผ่านของท่านไม่ตรงกัน</b>';
        header("location: ../signup.php");
        exit();
    } else {
        try {
            // ตรวจสอบอีเมลซ้ำ
            $check_email = $conn->prepare("SELECT email FROM users WHERE email = :email");
            $check_email->bindParam(":email", $email);
            $check_email->execute();
            $row = $check_email->fetch(PDO::FETCH_ASSOC);

            if ($row && $row['email'] == $email) {
                $_SESSION['warning'] = "<b>มีอีเมลนี้ในระบบอยู่แล้ว</b>";
                header("location: ../signup.php");
                exit();
            } else {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users(username, password, email, tel, urole) 
                                        VALUES(:username, :password, :email, :tel, :urole)");
                $stmt->bindParam(":username", $username);
                $stmt->bindParam(":password", $passwordHash);
                $stmt->bindParam(":email", $email);
                $stmt->bindParam(":tel", $tel);
                $stmt->bindParam(":urole", $urole);
                $stmt->execute();

                $_SESSION['success'] = "<b>สมัครสมาชิกสำเร็จ</b>";
                header("location: ../signup.php");
                exit();
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
