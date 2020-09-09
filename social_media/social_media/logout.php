<?php
//登入的表單

session_start();

unset($_SESSION['admin']);

# session_destroy(); // 清掉所有 session 資料

header('Location: WEB_ROOT2/../../../'); // redirect // 轉向