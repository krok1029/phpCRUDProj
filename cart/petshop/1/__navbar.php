<?php
if(empty($pageName)){
    $pageName = '';
}
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="#">Navbar</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= WEB_ROOT ?>/index_.php">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= WEB_ROOT ?>/product-list.php">商品列表</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= WEB_ROOT ?>/cart.php">購物車
                        <span class="badge badge-pill badge-primary cart-count">0</span>
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        快速檢視
                    </a>
                    <div class="dropdown-menu cart-short-list" aria-labelledby="navbarDropdown">
                        <!--
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        -->
                    </div>
                </li>
            </ul>
            <ul class="navbar-nav">
                <?php if(isset($_SESSION['member'])): ?>
                    <!--
                    <li class="nav-item">
                        <img src="http://www.gravatar.com/avatar/<?= md5($_SESSION['member']['email']) ?>.jpg?s=20" alt="">
                    </li>
                    -->
                    <!-- gmail avatar: https://jsfiddle.net/a4g8s5y2/1/ -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?= $_SESSION['member']['nickname'] ?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="#">歷史訂單</a>
                            <a class="dropdown-item" href="#">修改個人資料</a>
                        </div>
                    </li>


                    <li class="nav-item">
                        <a class="nav-link" href="<?= WEB_ROOT ?>/logout.php">登出</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= WEB_ROOT ?>/login.php">登入</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= WEB_ROOT ?>/register.php">註冊</a>
                    </li>
                <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>
<style>
    ul.navbar-nav > .nav-item.active {
        background-color: #9fcdff;
        border-radius: 10px;
    }
</style>