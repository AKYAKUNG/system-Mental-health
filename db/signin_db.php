<?php
session_start();
require_once 'db.php';

if (isset($_POST['signin'])) {
    $email      = $_POST['email'];
    $password   = $_POST['password'];

    // Validation
    if (empty($email)) {
        $_SESSION['error'] = 'กรุณากรอกอีเมลของท่าน';
        header("Location: ../signin.php");
        exit();
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['warning'] = 'รูปแบบอีเมลของท่านไม่ถูกต้อง';
        header("Location: ../signin.php");
        exit();
    } elseif (empty($password)) {
        $_SESSION['error'] = 'กรุณากรอกรหัสผ่านของท่าน';
        header("Location: ../signin.php");
        exit();
    } else {
        try {
            // ตรวจสอบข้อมูล
            $stmt = $conn->prepare("SELECT * FROM users WHERE email = :email");
            $stmt->bindParam(":email", $email);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                if ($password === $user['password']) {
                    // เก็บ session
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['urole'] = $user['urole'];

                    // Redirect ตาม role
                    switch ($user['urole']) {
                        case 'admin':
                            header("Location: ../admin.php");
                            break;
                        case 'edit_admin':
                            header("Location: ../edit_admin.php");
                            break;
                        default:
                            header("Location: ../user.php");
                    }
                    exit();

                } else {
                    $_SESSION['error'] = 'รหัสผ่านไม่ถูกต้อง';
                    header("Location: ../signin.php");
                    exit();
                }
            } else {
                $_SESSION['warning'] = 'ไม่พบข้อมูลในระบบ';
                header("Location: ../signin.php");
                exit();
            }

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            exit();
        }
    }
}
?>
