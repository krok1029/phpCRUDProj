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

$goods_id = $_GET['goods_id'];
$quantity = $_GET['quantity'];

$sql = "update `cart_list_01` set `quantity`=? WHERE `goods_id`=$goods_id";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    $quantity + 1,
]);

if ($stmt->rowCount()) {
    $output['success'] = true;
}

///////////////////

$goods_id = intval($_GET['goods_id']) ?? 0;
$sale = intval($_GET['sale']) ?? 0;

$sql = "UPDATE `shop_goods` SET `sale`=? WHERE `goods_id`=$goods_id";

$stmt2 = $pdo->prepare($sql);

$stmt2->execute([
    $sale + 1,
]);


if ($stmt2->rowCount()) {
    $output['success'] = true;
}

//////////////////

echo json_encode($output, JSON_UNESCAPED_UNICODE);

header('Location: ' . $referer);

//if(商品列表goods_id=購物車列表goods_id){
//if(is_buy=1){insert into `cart_list_01`...}
//if(is_buy=0){update `cart_list_01` set `quantity`=? WHERE `cart_id`=?;}}
//else{insert into`cart_list_01`...}