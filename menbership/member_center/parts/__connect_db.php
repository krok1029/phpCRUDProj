<?php

$db_host = "192.168.27.115";
$db_name = "pet_adoption_proj";
$db_user = "root";
$db_pass = "";

$dsn = "mysql:host={$db_host};dbname={$db_name}";

$pdo_options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"
];

//登入的功能
$pdo = new PDO($dsn, $db_user, $db_pass, $pdo_options);

# $pdo->query("use mytest;"); // 萬一出現 no databases selected 的錯誤

define('WEB_ROOT', '/phpCRUDProj/menbership/member_center');

if (!isset($_SESSION)) {
    session_start();
}
