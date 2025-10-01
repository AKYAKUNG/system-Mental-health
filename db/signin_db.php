<?php
session_start();
require_once 'db.php';

if (isset($_POST['signin'])) {
    $email      = $_POST['email'];
    $password   = $_POST['password'];

    // Validation

    if (empty($email)) {
        $_SESSION['error'] = '<b>กรุณากรอกอีเมลของท่าน</b>';
        header("location: ../signin.php");
        exit();
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['warning'] = '<b>รูปแบบอีเมลของท่านไม่ถูกต้อง</b>';
        header("location: ../signin.php");
        exit();
    } else if (empty($password)) {
        $_SESSION['error'] = '<b>กรุณาสร้างรหัสผ่านของท่าน</b>';
        header("location: ../signin.php");
        exit();
    } else {
        try {
            // ตรวจสอบข้อมูล
            $check_data = $conn->prepare("SELECT * FROM users WHERE email = :email");
            $check_data->bindParam(":email", $email);
            $check_data->execute();
            $row = $check_data->fetch(PDO::FETCH_ASSOC);

            if ($check_data->rowCount() > 0) {
                if ($email == $row['email']) {
                    if (password_verify($password, $row['password'])) {
                        if ($row['urole'] == 'admin') {
                            $_SESSION['admin_login'] = $row['id'];
                            header("location: ../admin.php");
                            exit();
                        } elseif ($row['urole'] == 'edit_admin') {
                            $_SESSION['edit_admin_login'] = $row['id'];
                            header("location: ../edit_admin.php");
                            exit();
                        } else {
                            $_SESSION['user_login'] = $row['id'];
                            header("location: ../user.php");
                            exit();
                        }
                    } else {
                        $_SESSION['error'] = '<b>รหัสผ่านไม่ถูกต้อง</b>';
                        header("location: ../signin.php");
                        exit();
                    }
                } else {
                    $_SESSION['error'] = '<b>อีเมลไม่ถูกต้อง</b>';
                    header("location: ../signin.php");
                    exit();
                }
            } else {
                $_SESSION['warning'] = "<b>ไม่พบข้อมูลในระบบ</b>";
                header("location: ../signin.php");
                exit();
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
