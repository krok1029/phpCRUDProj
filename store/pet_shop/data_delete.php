<?php
require __DIR__. '/parts/__connect_db.php';
require __DIR__. '/parts/__admin_required.php';

$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'data_list.php';

if(empty($_GET['goods_id'])){
    header('Location: '. $referer);
    exit;
}
$goods_id = intval($_GET['goods_id']) ?? 0;

$pdo->query("DELETE FROM shop_goods WHERE goods_id=$goods_id ");
header('Location: '. $referer);
