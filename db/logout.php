<?php
    session_start();
    unset($_SESSION['user_login']);
    unset($_SESSION['admin_login']);
    unset($_SESSION['edit_admin_login']);
    header('location:../signin.php');
?>