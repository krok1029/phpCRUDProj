<?php
require __DIR__. '/parts/__connect_db.php';
require __DIR__. '/parts/__admin_required.php';
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'data-list.php';

if(empty($_GET['sid'])){
    header('Location: '. $referer);
    exit;
}
$sid = intval($_GET['sid']) ?? 0;

$pdo->query("DELETE FROM shop_goods WHERE sid=$sid ");
header('Location: '. $referer);

