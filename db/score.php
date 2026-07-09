<?php
session_start();
require_once 'db.php';

if (isset($_POST['answer'])) {
    $user_id = $_SESSION['user_id'];

    // รวมคะแนน
    $total = 0;
    for ($i = 1; $i <= 10; $i++) {
        if (!isset($_POST["q$i"])) {
            $_SESSION['error'] = "<b>ท่านยังไม่ตอบคำถามข้อที่ $i</b>";
            header('Location: ../test.php');
            exit();
        }
        $total += (int)$_POST["q$i"];
    }

    // ตรวจสอบว่า user วันนี้บันทึกแล้วหรือยัง
    $check_date = $conn->prepare("
        SELECT created_at 
        FROM score 
        WHERE users_id = :users_id 
          AND DATE(created_at) = CURDATE()
    ");
    $check_date->bindParam(":users_id", $user_id, PDO::PARAM_INT);
    $check_date->execute();
    $row = $check_date->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        // ถ้ามีข้อมูลแล้ว
        $_SESSION['error'] = "<b>วันนี้คุณแบบสอบถามในวันนี้แล้ว!</b>";
        header("Location: ../test.php");
        exit();
    }

    // ถ้ายังไม่มีข้อมูลวันนี้ → บันทึกใหม่
    try {
        $stmt = $conn->prepare("
            INSERT INTO score (users_id, total_score)
            VALUES (:user_id, :total_score)
        ");
        $stmt->bindParam(":user_id", $user_id, PDO::PARAM_INT);
        $stmt->bindParam(":total_score", $total, PDO::PARAM_INT);
        $stmt->execute();

        $_SESSION['success'] = "<b>บันทึกข้อมูลสำเร็จ</b>";
        header("Location: ../user.php");
        exit();
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}
?>