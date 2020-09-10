<?php
require __DIR__ . '/parts/__connect_db.php';
require __DIR__ . '/parts/__admin_required.php';

// $perPage = 5; // 每頁有幾筆資料
// $output = [
//     'perPage' => $perPage,
//     'totalRows' => 0,
//     'totalPages' => 0,
//     'page' => 0,
//     'rows' => [],
// ];

// $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// $t_sql = "SELECT COUNT(1) FROM `cart_list_01`";

// $output['totalRows'] = $totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];
// $output['totalPages'] = $totalPages = ceil($totalRows / $perPage);

// if ($totalRows > 0) {
//     if ($page < 1) $page = 1;
//     if ($page > $totalPages) $page = $totalPages;
//     $output['page'] = $page;

//     $sql = sprintf("SELECT * FROM `cart_list_01` ORDER BY cart_id DESC LIMIT %s, %s", ($page - 1) * $perPage, $perPage);
//     $stmt = $pdo->query($sql);
//     $output['rows'] = $stmt->fetchAll();
// }

///////////////

$output = [
    'success' => false,
    'postData' => $_POST,
    'code' => 0,
    'error' => ''
];

foreach ($_POST['cart_id_check'] as $check_id) {
    $sql = "update `cart_list_01` set `buy_now`=? WHERE `cart_id`=?";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        1,
        $check_id
    ]);

    $output['success'] = true;
}



echo json_encode($output, JSON_UNESCAPED_UNICODE);
