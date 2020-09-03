<?php
require __DIR__ . '/__connect.php';

require __DIR__ . '/__admin_required.php';

$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'data-list.php';

if (empty($_GET['sid'])) {
    header('Location: ' . $referer);
    exit;
}
$sid = intval($_GET['sid']) ?? 0;

$pdo->query("DELETE FROM address_book WHERE sid=$sid ");
header('Location: ' . $referer);
