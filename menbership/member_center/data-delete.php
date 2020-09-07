<?php
require __DIR__ . '/parts/__connect_db.php';
//新增未登入的功能限制
require __DIR__ . '/parts/__admin_required.php';

//刪除資料
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'member_list.php';

if (empty($_GET['sid'])) {
    header('Location: ' . $referer);
    exit;
}
$sid = intval($_GET['sid']) ?? 0;

$pdo->query("DELETE FROM member_list WHERE sid=$sid ");
header('Location: ' . $referer);
