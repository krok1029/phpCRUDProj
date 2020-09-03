<?php

//$db_host = "192.168.27.82";
$db_host = "localhost";
$db_name = "test";
$db_user = "Wayne";
$db_pass = "tsun227";

$dsn = "mysql:host={$db_host};dbname={$db_name}";

$pdo_options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"
];

$pdo = new PDO($dsn, $db_user, $db_pass, $pdo_options);

# $pdo->query("use mytest;"); // 萬一出現 no databases selected 的錯誤

define('WEB_ROOT', '/phpCRUDProj/homepage/homepage');

if (!isset($_SESSION)) {
    session_start();
}
