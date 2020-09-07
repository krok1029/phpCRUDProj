<?php
require __DIR__ . '/__connect_db.php';

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
 ) VALUES (?, ?,?,?,?, NOW())";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    $_POST['admins_id'],
    $_POST['total_price'],
    $_POST['nickname'],
    $_POST['address'],
    $_POST['cellphone'],
]);

if ($stmt->rowCount()) {
    $output['success'] = true;
    // $output['order_id'] = $stmt->lastInsertId();
}

/////////////////////

// $sql = "update `cart_list_01` SET `nickname`=?, `address`=?,`cellphone`=? where cart_id=?";

// $stmt2 = $pdo->prepare($sql);
// $stmt2->execute([
//     $_POST['nickname'],
//     $_POST['address'],
//     $_POST['cellphone'],
//     $_POST['cart_id'],
// ]);

// if ($stmt2->rowCount()) {
//     $output['success'] = true;
// }

///////////////////
// $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'order_insert.php';
///////////////////

// if (empty($_GET['cart_id'])) {
//     header('Location: ' . $referer);
//     exit;
// }

// $cart_id = intval($_GET['cart_id']) ?? 0;

// $stmt3 = $pdo->query("INSERT INTO `order_list_02` (`goods_id`,`quantity`)
// SELECT `goods_id`, `quantity` FROM `cart_list_01` WHERE cart_id = $cart_id");


// if ($stmt3->rowCount()) {
//     $output['success'] = true;
// }

///////////////////

// if (empty($_GET['order_id'])) {
//     header('Location: ' . $referer);
//     exit;
// }

// $order_id = intval($_GET['order_id']) ?? 0;

// $stmt4 = $pdo->query("insert into `order_list_02` (`order_id`)
// SELECT `order_id` FROM `order_list_01`");

// if ($stmt4->rowCount()) {
//     $output['success'] = true;
// }

/////////////////////

echo json_encode($output, JSON_UNESCAPED_UNICODE);
