<?php
require __DIR__ . '/parts/__connect_db.php';
require __DIR__ . '/parts/__admin_required.php';
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



// INSERT INTO `pet_info_master`(`pet_id`, `name`, `dog_cat`, `age`, `area`, `address`, `description`, `create_time`) 
$sql = "INSERT INTO `pet_info_master`(
 `name`, `dog_cat`, `age`, `area`, `address`, `description`, `create_time`
 ) VALUES (?, ?, ?, ?, ?, ?, NOW())";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    $_POST['name'],
    $_POST['dog_cat'],
    $_POST['age'],
    $_POST['area'],
    $_POST['address'],
    $_POST['description'],
]);

///////// handle tags
if (count($_POST['tags']) > 0) {
    $sql = "select pet_id from pet_info_master where name = ? and dog_cat = ? and age = ? and area = ? and address = ? and description = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['name'],
        $_POST['dog_cat'],
        $_POST['age'],
        $_POST['area'],
        $_POST['address'],
        $_POST['description'],
    ]);
    $pet_id = $stmt->fetch(PDO::FETCH_NUM)[0];


    $sql = "insert into pet_info_detail(pet_id,tag_id) values";
    $sql2 = "";

    for ($i = 0; $i < count($_POST['tags']); $i++) {

        $sql2 = $sql2 . "( " . $pet_id . " , " . $_POST['tags'][$i] . " ) ";

        if ($i < count($_POST['tags']) - 1) {
            $sql2 = $sql2 . ",";
        }
    }

    $sql = $sql . $sql2;

    if (isset($_POST['tags'])) {
        $stmt = $pdo->query($sql);
    }
}
if ($stmt->rowCount()) {
    $output['success'] = true;
}

echo json_encode($output, JSON_UNESCAPED_UNICODE);
