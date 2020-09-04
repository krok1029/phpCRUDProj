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



// if(1){
//     $output['user_id'] = $_POST['user_id'];
//     $output['dog_cat'] = $_POST['dog_cat'];
//     $output['age'] = $_POST['age'];
//     $output['area'] = $_POST['area'];
//     $output['sid'] = $_POST['sid'];
//     $output['code'] = 405;
//     $output['error'] = '沒有 sid';
//     echo json_encode($output, JSON_UNESCAPED_UNICODE);
//     exit;
// }

if(empty($_POST['sid'])){
    $output['code'] = 405;
    $output['error'] = '沒有 sid';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}




$sql = "UPDATE `pet_info` SET 
    `user_id`=?,
    `dog_cat`=?,
    `age`=?,
    `area`=?
    WHERE `sid`=?";

$stmt = $pdo->prepare($sql);
$stmt->execute([
        $_POST['user_id'],
        $_POST['dog_cat'],
        $_POST['age'],
        $_POST['area'],
        $_POST['sid'],
]);

if($stmt->rowCount()){
    $output['success'] = true;
}

echo json_encode($output, JSON_UNESCAPED_UNICODE);
