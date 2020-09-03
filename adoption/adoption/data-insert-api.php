<?php
require __DIR__. '/parts/__connect_db.php';
require __DIR__. '/parts/__admin_required.php';
header('Content-Type: application/json');

$output = [
    'success' => false,
    'postData' => $_POST,
    'code' => 0,
    'error' => ''
];

// TODO: 檢查資料格式
// email_pattern = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
// mobile_pattern = /^09\d{2}-?\d{3}-?\d{3}$/;



if(mb_strlen($_POST['name'])<2){
    $output['code'] = 410;
    $output['error'] = '姓名長度要大於 2';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}




$sql = "INSERT INTO `shop_goods`(
`name`, `type`, `brand`,
 `pricing`, `price`, `created_at`
 ) VALUES (?, ?, ?, ?, ?, NOW())";

$stmt = $pdo->prepare($sql);
$stmt->execute([
        $_POST['name'],
        $_POST['type'],
        $_POST['brand'],
        $_POST['pricing'],
        $_POST['price'],
]);

if($stmt->rowCount()){
    $output['success'] = true;
}

echo json_encode($output, JSON_UNESCAPED_UNICODE);
