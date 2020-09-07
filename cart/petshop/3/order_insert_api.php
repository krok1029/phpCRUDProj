<?php
require __DIR__ . '/__connect_db.php';

header('Content-Type: application/json');

$output = [
    'success' => false,
    'postData' => $_POST,
    'code' => 0,
    'error' => ''
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
`admins_id`, `total_price`,`created_at`
 ) VALUES (?, ?, NOW())";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    $_POST['admins_id'],
    $_POST['total_price'],
]);

if ($stmt->rowCount()) {
    $output['success'] = true;
}
echo "1>>" + $stmt;
/////////////////////

$sql2 = "INSERT INTO `order_list_02`(
    `nickname`, `address`,`cellphone`
     ) VALUES (?, ?, ?)";

$stmt2 = $pdo->prepare($sql2);
$stmt2->execute([
    $_POST['nickname'],
    $_POST['address'],
    $_POST['cellphone'],
]);

if ($stmt2->rowCount()) {
    $output['success'] = true;
}

echo "2>>" + $stmt2;
///////////////////

$stmt3 = $pdo->query("insert into `order_list_02` (`goods_id`,`quantity`)
SELECT `goods_id`, `quantity` FROM `cart_list_01` WHERE cart_id = $cart_id");


if ($stmt3->rowCount()) {
    $output['success'] = true;
}
echo "3>>" + $stmt3;
///////////////////

$stmt4 = $pdo->query("insert into `order_list_02` (`order_id`)
SELECT `order_id`  FROM `order_list_01` WHERE order_id = $order_id");

if ($stmt4->rowCount()) {
    $output['success'] = true;
}
echo "4>>" + $stmt4;
/////////////////////

echo json_encode($output, JSON_UNESCAPED_UNICODE);
