<?php
if (!isset($page_name)) $page_name = '';
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
    <a class="navbar-brand" href="http://localhost/phpCRUDProj/">寵物後台 -</a>
        <a class="navbar-brand" href="#">會員中心</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item <?= $page_name == 'member_list' ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= WEB_ROOT ?>/member_list.php">會員列表</a>
                </li>
                <li class="nav-item <?= $page_name == 'data-insert' ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= WEB_ROOT ?>/data-insert.php">會員新增</a>
                </li>
                <!-- <li class="nav-item <?= $page_name == 'data-list2' ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= WEB_ROOT ?>/data-list2.php">列表2(ajax)</a>
                </li> -->
            </ul>
            <!-- 未登入的功能限制 -->
            <ul class="navbar-nav">
                <?php if (isset($_SESSION['admin'])) : ?>
                    <li class="nav-item">
                        <a class="nav-link"><?= $_SESSION['admin']['nickname'] ?></a>
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
        </div>
    </div>
</nav>
<style>
    .navbar .nav-item.active {
        background-color: #7abaff;
        border-radius: 10px;
    }
</style>