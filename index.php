<?php

$db_host = "192.168.27.115";
//$db_host = "localhost";
$db_name = "pet_adoption_proj";
$db_user = "root";
$db_pass = "";

$dsn = "mysql:host={$db_host};dbname={$db_name}";

$pdo_options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"
];

$pdo = new PDO($dsn, $db_user, $db_pass, $pdo_options);

# $pdo->query("use mytest;"); // 萬一出現 no databases selected 的錯誤

define('WEB_ROOT', '/phpCRUDProj/store/pet_shop');

if (!isset($_SESSION)) {
    session_start();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="<?= WEB_ROOT ?>/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="<?= WEB_ROOT ?>/fontawesome/css/all.css">
    <title><?= $page_title ?? '' ?> 寵物後台</title>
</head>
<body>

<?php
if(! isset($page_name)) $page_name='';
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="http://localhost/phpCRUDProj/">寵物後台</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item <?= $page_name=='data_list' ? 'active' : '' ?>">
                    <a class="nav-link" href="./homepage/homepage/pet_info/data-list.php">首頁</a>
                </li>
                <li class="nav-item <?= $page_name=='data_insert' ? 'active' : '' ?>">
                    <a class="nav-link" href="./menbership/member_center/member_list.php">會員</a>
                </li>
                <li class="nav-item <?= $page_name=='data_insert' ? 'active' : '' ?>">
                    <a class="nav-link" href="./store/pet_shop/data_list.php">商城</a>
                </li>
                <li class="nav-item <?= $page_name=='data_insert' ? 'active' : '' ?>">
                    <a class="nav-link" href="http://localhost/phpCRUDProj/store/pet_shop/data_list_test.php">購物車</a>
                </li>
                <li class="nav-item <?= $page_name=='data_insert' ? 'active' : '' ?>">
                    <a class="nav-link" href="./social_media/social_media/data-list.php">社群</a>
                </li>
                <li class="nav-item <?= $page_name=='data_insert' ? 'active' : '' ?>">
                    <a class="nav-link" href="./adoption/adoption/data-list.php">動物領養</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <?php if(isset($_SESSION['admin1'])): ?>
                    <li class="nav-item">
                        <a class="nav-link"><?= $_SESSION['admin1']['nickname'] ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= WEB_ROOT ?>/logout.php">登出</a>
                    </li>

                <?php else: ?>
                    <li class="nav-item <?= $page_name=='login' ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= WEB_ROOT ?>/login.php">登入</a>
                    </li>
                <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>
<style>
    .navbar .nav-item.active {
        background-color: #7abaff;
        border-radius: 10px;
    }
</style>



<div class="container center">
    <h3>Hello~各位，我們要進來了ヽ(∀ﾟ )人(ﾟ∀ﾟ)人( ﾟ∀)人(∀ﾟ )人(ﾟ∀ﾟ)人( ﾟ∀)ﾉ~<i id="close_picture" class="fas fa-heart"></i></h3>
    <img style="width:88%;" src="./2000x.gif">
</div>

<script src="<?= WEB_ROOT ?>/lib/jquery-3.5.1.min.js"></script>
<script src="<?= WEB_ROOT ?>/bootstrap/js/bootstrap.js"></script>
<script>
    document.getElementById('close_picture')
</script>
</body>
</html>

<!-- 以上大家的首頁整理中 -->
<!-- <?php require __DIR__. '/parts/__html_head.php'; ?>
<?php include __DIR__. '/parts/__navbar.php'; ?>
<div class="container">
    <h2>Hello~</h2>

</div>
<?php include __DIR__. '/parts/__scripts.php'; ?>
<?php include __DIR__. '/parts/__html_foot.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
</head>

<body>
    <h2>後臺管理</h2>
    <a href="./homepage/homepage/pet_info/data-list.php">首頁</a>
    <a href="./menbership/index.php">會員</a>
    <a href="./store/index.php">商城</a>
    <a href="./cart/index.php">購物車</a>
    <a href="./social_media/index.php">社群</a>
    <a href="./adoption/index.php">動物領養</a>


    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
</body>

</html> -->