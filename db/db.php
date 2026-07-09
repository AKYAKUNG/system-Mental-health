<?php
$servername = "localhost";
$username = "root";
$password = "12345678";

try {
  // เพิ่ม charset=utf8mb4
  $conn = new PDO(
    "mysql:host=$servername;dbname=dbsystem;charset=utf8mb4",
    $username,
    $password
  );

  // ตั้งค่า error mode
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // ตั้งให้ผลลัพธ์จาก DB เป็น UTF-8 ด้วย
  $conn->exec("SET NAMES utf8mb4");

  //echo "Connected successfully";
} catch (PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}