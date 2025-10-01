<?php
session_start();
require_once 'db.php';

if (isset($_POST['answer'])) {
    $user_id = $_SESSION['user_login'];
    $q1 = $_POST['q1'];
    $q2 = $_POST['q2'];
    $q3 = $_POST['q3'];
    $q4 = $_POST['q4'];
    $q5 = $_POST['q5'];
    $q6 = $_POST['q6'];
    $q7 = $_POST['q7'];
    $q8 = $_POST['q8'];
    $q9 = $_POST['q9'];
    $q10 = $_POST['q10'];
    $total = $q1 + $q2 + $q3 + $q4 + $q5 + $q6 + $q7 + $q8 + $q9 + $q10;

    // ตรวจสอบว่าตอบครบ
    for ($i=1; $i<=10; $i++) {
        if (empty($_POST["q$i"])) {
            $_SESSION['error'] = "ท่านยังไม่ตอบคำถามข้อที่ $i";
            header('Location: ../user.php');
            exit();
        }
    }

    try {
        $stmt = $conn->prepare("
            INSERT INTO scores
            (users_id, q1, q2, q3, q4, q5, q6, q7, q8, q9, q10, total_score)
            VALUES
            (:user_id, :q1, :q2, :q3, :q4, :q5, :q6, :q7, :q8, :q9, :q10, :total_score)
        ");
        $stmt->bindParam(":user_id", $user_id);
        $stmt->bindParam(":q1", $q1);
        $stmt->bindParam(":q2", $q2);
        $stmt->bindParam(":q3", $q3);
        $stmt->bindParam(":q4", $q4);
        $stmt->bindParam(":q5", $q5);
        $stmt->bindParam(":q6", $q6);
        $stmt->bindParam(":q7", $q7);
        $stmt->bindParam(":q8", $q8);
        $stmt->bindParam(":q9", $q9);
        $stmt->bindParam(":q10", $q10);
        $stmt->bindParam(":total_score", $total);
        $stmt->execute();

        $_SESSION['success'] = "<b>บันทึกข้อมูลสำเร็จ</b>";
        header("Location: ../user.php");
        exit();
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}
?>
