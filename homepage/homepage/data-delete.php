<?php
require __DIR__ . '/parts/__connect_db.php';
require __DIR__ . '/parts/__admin_required.php';
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'data-list.php';

// echo $_GET['pet_id'];

if (empty($_GET['pet_id'])) {
    header('Location: ' . $referer);
    exit;
}
$pet_id = intval($_GET['pet_id']) ?? 0;

$pdo->query("DELETE FROM pet_info_master WHERE pet_id=$pet_id ");
header('Location: ' . $referer);