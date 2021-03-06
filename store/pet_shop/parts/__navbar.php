<?php
if (!isset($page_name)) $page_name = '';
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="http://localhost/phpCRUDProj/#">寵物後台 -</a>
        <a class="navbar-brand" href="#">寵物商城</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item <?= $page_name == 'data_list' ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= WEB_ROOT ?>/data_list.php">商品列表</a>
                </li>
                <li class="nav-item <?= $page_name == 'data_insert' ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= WEB_ROOT ?>/data_insert.php">商品新增</a>
                </li>
                <li class="nav-item <?= $page_name == 'data_list_test' ? 'active' : '' ?>">
                    <a class="nav-link" href="./data_list_test.php">購物車列表</a>
                </li>
            </ul>
            <!-- 登入 -->
            <ul class="navbar-nav">
                <?php if (isset($_SESSION['admin1'])) : ?>
                    <li class="nav-item">
                        <a class="nav-link"><?= $_SESSION['admin1']['nickname'] ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= WEB_ROOT ?>/logout.php">登出</a>
                    </li>

                <?php else : ?>
                    <li class="nav-item <?= $page_name == 'login' ? 'active' : '' ?>">
                        <a class="nav-link" href="<?= WEB_ROOT ?>/login.php">登入</a>
                    </li>
                <?php endif; ?>
            </ul>
            <!-- 登入結束 -->
        </div>
    </div>
</nav>
<style>
    .navbar .nav-item.active {
        background-color: #7abaff;
        border-radius: 10px;
    }
</style>