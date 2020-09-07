<?php
require __DIR__ . '/parts/__connect_db.php';
//新增未登入的功能限制
// require __DIR__ . '/parts/__admin_required.php';
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



if (mb_strlen($_POST['name']) < 2) {
    $output['code'] = 410;
    $output['error'] = '姓名長度要大於 2';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

if (!preg_match('/^\d{2}-\d{4}-\d{4}$/', $_POST['telephone'])) {
    $output['code'] = 420;
    $output['error'] = '連絡電話格式錯誤';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

if (!preg_match('/^09\d{2}-?\d{3}-?\d{3}$/', $_POST['mobile'])) {
    $output['code'] = 430;
    $output['error'] = '行動電話格式錯誤';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}


$sql = "INSERT INTO `member_list`(
`name`, `birthday`, `telephone`, `mobile`,
 `email`, `address`, `created_at`
 ) VALUES (?, ?, ?, ?, ?, ?, NOW())";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    $_POST['name'],
    $_POST['birthday'],
    $_POST['telephone'],
    $_POST['mobile'],
    $_POST['email'],
    $_POST['address'],
]);

if ($stmt->rowCount()) {
    $output['success'] = true;
}

echo json_encode($output, JSON_UNESCAPED_UNICODE);
