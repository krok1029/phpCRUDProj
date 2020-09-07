<?php
require __DIR__ . '/parts/__connect_db.php';

$perPage = 4; // 每頁有幾筆資料
$output = [
    'perPage' => $perPage,
    'totalRows' => 0,
    'totalPages' => 0,
    'page' => 0,
    'rows' => [],
];

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

$t_sql = "SELECT COUNT(1) FROM `pet_info_master`";
$output['totalRows'] = $totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];
$output['totalPages'] = $totalPages = ceil($totalRows / $perPage);

if ($totalRows > 0) {
    if ($page < 1) $page = 1;
    if ($page > $totalPages) $page = $totalPages;
    $output['page'] = $page;

    $sql = sprintf("SELECT * FROM `pet_info_master` ORDER BY pet_id DESC LIMIT %s, %s", ($page - 1) * $perPage, $perPage);
    $stmt = $pdo->query($sql);
    $output['rows'] = $stmt->fetchAll();
}

echo json_encode($output, JSON_UNESCAPED_UNICODE);
