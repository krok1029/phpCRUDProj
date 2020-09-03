<?php
require __DIR__ . '/__connect_db.php';

require __DIR__ . '/__admin_required.php';

$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'data_list_test.php';

if (empty($_GET['order_id'])) {
    header('Location: ' . $referer);
    exit;
}
$order_id = intval($_GET['order_id']) ?? 0;

$pdo->query("DELETE FROM cart_list_01 WHERE order_id=$order_id ");
header('Location: ' . $referer);
