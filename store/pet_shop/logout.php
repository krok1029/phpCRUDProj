<?php

session_start();

unset($_SESSION['admin1']);

# session_destroy(); // 清掉所有 session 資料

header('Location: data_list.php'); // redirect // 轉向

