<?php
//未登入的功能限制

if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['admin'])) {
    //若無登入，跳轉到登入頁面
    header('Location: login.php');

    exit;
}
