<?php

$db_host = "192.168.27.115";
//$db_host = "localhost";
$db_name = "pet_adoption_proj";
$db_user = "root";
$db_pass = "";
// $db_host = "localhost";
// $db_name = "pet_social_media";
// $db_user = "afei";
// $db_pass = "smilefrog";

$dsn = "mysql:host={$db_host};dbname={$db_name}";

$pdo_options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"
];

$pdo = new PDO($dsn, $db_user, $db_pass, $pdo_options);

define('WEB_ROOT', '/phpCRUDProj/social_media/social_media');

if (!isset($_SESSION)) {
    session_start();
}
