<?php
require __DIR__. '/parts/__connect_db.php';

header('Content-Type: application/json');

$output = [
    'success' => false,
    'postData' => $_POST,
    'code' => 0,
    'error' => ''
];

$account = isset($_POST['account']) ? $_POST['account'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

$sql = "SELECT `pet_shop_admins_id`, `account`, `nickname` 
        FROM `pet_shop_admins` 
        WHERE `account`=? AND `password`=SHA1(?)";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    $account,
    $password,
]);

if($stmt->rowCount()){
    $output['success'] = true;
    $_SESSION['admin1'] = $stmt->fetch();
}

echo json_encode($output, JSON_UNESCAPED_UNICODE);
