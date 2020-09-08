<?php
require __DIR__ . '/parts/__connect_db.php';
// require __DIR__ . '/parts/__admin_required.php';
header('Content-Type: application/json');

$output = [
    'success' => false,
    'postData' => $_POST,
    'code' => 0,
    'error' => ''
];

$sql = "UPDATE `cart_list_01` SET 
    `quantity`=?
    WHERE `cart_id`=?";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    $_POST['quantity'],
    $_POST['cart_id'],
]);

if ($stmt->rowCount()) {
    $output['success'] = true;
}

echo json_encode($output, JSON_UNESCAPED_UNICODE);
