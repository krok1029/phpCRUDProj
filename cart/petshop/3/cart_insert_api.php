<?php
require __DIR__ . '/__connect_db.php';
require __DIR__ . '/__admin_required.php';
header('Content-Type: application/json');

$output = [
    'success' => false,
    'postData' => $_POST,
    'code' => 0,
    'error' => ''
];


$sql = "INSERT INTO `cart_list_01`(
 `admins_id`,`name`, `price`,`created_at`
 ) VALUES (?, ?, ?, NOW())";

$stmt = $pdo->prepare($sql);
$stmt->execute([

    $_SESSION['admin'],
    $_POST['name'],

    $_POST['price'],
]);

//`goods_id`,
//`quantity`,
//$_POST['goods_id'],
//$_POST['quantity'],
//判斷重複商品

if ($stmt->rowCount()) {
    $output['success'] = true;
}

echo json_encode($output, JSON_UNESCAPED_UNICODE);
