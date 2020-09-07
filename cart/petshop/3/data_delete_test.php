<?php
require __DIR__ . '/__connect_db.php';

require __DIR__ . '/__admin_required.php';

$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'data_list_test.php';

if (empty($_GET['cart_id'])) {
    header('Location: ' . $referer);
    exit;
}
$cart_id = intval($_GET['cart_id']) ?? 0;

$pdo->query("DELETE FROM cart_list_01 WHERE cart_id=$cart_id ");
header('Location: ' . $referer);
