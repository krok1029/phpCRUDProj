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


$sql = "INSERT INTO `pet_info`(
 `user_id`, `dog_cat`, `age`, `area`
 ) VALUES (?, ?, ?, ?)";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    $_POST['user_id'],
    $_POST['dog_cat'],
    $_POST['age'],
    $_POST['area'],
]);

if (count($_POST['tags']) > 0) {
    // $sql = "select user_id from pet_info where user_id = ? and dog_cat = ? and age = ? and area = ? ";

    // $stmt = $pdo->prepare($sql);
    // $stmt->execute([
    //     $_POST['user_id'],
    //     $_POST['dog_cat'],
    //     $_POST['age'],
    //     $_POST['area'],

    // ]);
    // $pet_id = $stmt->fetch(PDO::FETCH_NUM)[0];


    $sql = "insert into pet_info_detail(user_id,tag_id) values";
    $sql2 = "";
    $user_id =$_POST['user_id'] ;
    for ($i = 0; $i < count($_POST['tags']); $i++) {

        $sql2 = $sql2 . "( " . $user_id . " , " . $_POST['tags'][$i] . " ) ";

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
