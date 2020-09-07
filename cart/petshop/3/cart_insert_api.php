<?php
require __DIR__ . '/__connect_db.php';
require __DIR__ . '/__admin_required.php';
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'data-list.php';

$output = [
    'success' => false,
    'postData' => $_POST,
    'code' => 0,
    'error' => ''
];

if (empty($_GET['goods_id'])) {
    header('Location: ' . $referer);
    exit;
}
$goods_id = intval($_GET['goods_id']) ?? 0;

$stmt = $pdo->query("insert into `cart_list_01` (`goods_id`,`name`,`price`,`quantity`, `created_at`)
SELECT `goods_id`, `name`,  `price`, 1, NOW()  FROM `shop_goods` WHERE goods_id = $goods_id");


if ($stmt->rowCount()) {
    $output['success'] = true;
}

echo json_encode($output, JSON_UNESCAPED_UNICODE);

header('Location: ' . $referer);
