<?php
require __DIR__ . '/parts/__connect_db.php';

header('Content-Type: application/json');

$output = [
    'success' => false,
    'postData' => $_POST,
    'code' => 0,
    'error' => '',
    'order_id' => 0
];

//cellphone_pattern = /^09\d{2}-?\d{3}-?\d{3}$/;

if (mb_strlen($_POST['nickname']) < 2) {
    $output['code'] = 410;
    $output['error'] = '收件人名稱長度要大於 2';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

if (!preg_match('/^09\d{2}-?\d{3}-?\d{3}$/', $_POST['cellphone'])) {
    $output['code'] = 420;
    $output['error'] = '手機號碼格式錯誤';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}


$sql = "INSERT INTO `order_list_01`(
    `admins_id`, `total_price`,`nickname`,`address`,`cellphone`,`created_at`
     ) VALUES (?,?,?,?,?, NOW())";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    $_POST['pet_shop_admins_id'],
    $_POST['total_price'],
    $_POST['nickname'],
    $_POST['address'],
    $_POST['cellphone'],
]);

if ($stmt->rowCount()) {
    $output['success'] = true;
}



$order_id =  $pdo->lastInsertId();

$sql = "UPDATE `cart_list_01` SET `order_id`=$order_id,`is_buy`=1,`buy_now`=2 WHERE `cart_id`=?";
$stmt = $pdo->prepare($sql);
//變陣列
foreach (explode(', ', $_POST['cartIdArray'])  as $a) {

    $stmt->execute([
        $a,
    ]);

    if ($stmt->rowCount()) {
        $output['success'] = true;
    }
}


echo json_encode($output, JSON_UNESCAPED_UNICODE);
