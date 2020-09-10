<?php
require __DIR__ . '/parts/__connect_db.php';
require __DIR__ . '/parts/__admin_required.php';
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'data_list.php';

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
$sid = intval($_GET['sid']) ?? 0;
// 上為登入帳號，在myadmin的pet_shop_admin
$price = intval($_GET['price']) ?? 0;
$name = $_GET['name'];

//if(商品列表goods_id=購物車列表goods_id){
//if(is_buy=1){insert into `cart_list_01`...}
//if(is_buy=0){update `cart_list_01` set `quantity`=? WHERE `cart_id`=?;}}
//else{insert into`cart_list_01`...}

$sql = "insert into `cart_list_01` (`goods_id`,`admins_id`,`name`,`price`,`quantity`, `created_at`) VALUES (?, ?, ?, ? , ?, NOW())";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    $goods_id,
    $sid,
    $name,
    $price,
    1,
]);


if ($stmt->rowCount()) {
    $output['success'] = true;
}

///////////////////

// $sale = intval($_GET['sale']) ?? 0;


// $sql = "UPDATE `shop_goods` SET `sale`=? WHERE `goods_id`=$goods_id";

// $stmt2 = $pdo->prepare($sql);

// $stmt2->execute([
//     $sale + 1,
// ]);


// if ($stmt2->rowCount()) {
//     $output['success'] = true;
// }

//////////////////

echo json_encode($output, JSON_UNESCAPED_UNICODE);

header('Location: ' . $referer);
