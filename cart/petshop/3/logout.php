<?php

session_start();

unset($_SESSION['admin']);

# session_destroy(); // 清掉所有 session 資料

header('Location: data_list_test.php'); // redirect // 轉向
