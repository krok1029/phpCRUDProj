<?php
require __DIR__ . './parts/__connect_db.php';
require __DIR__ . './parts/__admin_required.php';


header('Content-Type: application/json');

$output = [
    'success' => false,
    'postData' => $_POST,
    'code' => 0,
    'error' => ''
];

// TODO: 檢查資料格式



if (empty($_POST['sid'])) {
    $output['code'] = 405;
    $output['error'] = '沒有sid';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

if (mb_strlen($_POST['title']) < 2) {
    $output['code'] = 410;
    $output['error'] = '標題長度要大於 2';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}
if (mb_strlen($_POST['content']) < 15) {
    $output['code'] = 420;
    $output['error'] = '內容長度要大於 15';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}


$sql = "UPDATE `forum_article` SET 
`type_sid`=?,
`issue_sid`=?,
`title`=?,
`content`=?,
`picture`=?
WHERE `sid`=?";

$sq2 = "UPDATE `forum_article` SET 
`Last_updated`=NOW()
WHERE `sid`=?";


$stmt = $pdo->prepare($sql);
$stmt2 = $pdo->prepare($sq2);

$stmt->execute([
    $_POST['ptype_sid'],
    $_POST['issue_sid'],
    $_POST['title'],
    $_POST['content'],
    $_POST['picture'],
    $_POST['sid'],
]);

// echo $stmt->rowCount();
// echo 'ok';
if ($stmt->rowCount()) {
    $output['success'] = true;
    $stmt2->execute([
        $_POST['sid'],
    ]);
}

echo json_encode($output, JSON_UNESCAPED_UNICODE);
