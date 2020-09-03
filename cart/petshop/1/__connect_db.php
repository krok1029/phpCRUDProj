<?php
$db_host = 'localhost';
$db_name = 'test';
$db_user = 'anne';
$db_pass = '1215';


$dsn = sprintf('mysql:host=%s;dbname=%s;charset=utf8', $db_host, $db_name);

$pdo_options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
];

$pdo = new PDO($dsn, $db_user, $db_pass, $pdo_options);
// $pdo->query("USE `proj58`"); // 選擇使用的資料庫

if (!isset($_SESSION)) {
    session_start();
}

define('WEB_ROOT', '/petshop');
