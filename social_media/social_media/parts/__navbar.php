<?php
if (!isset($page_name)) $page_name = '';
?>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container">
  <a class="navbar-brand" href="http://localhost/phpCRUDProj/">寵物後台 -</a>
    <a class="navbar-brand" href="#">寵物社群</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item <?= $page_name == 'data-list' ? 'active' : '' ?> ">
          <a class="nav-link" href="<?= WEB_ROOT2 ?>/data-list.php">文章列表</a>
        </li>
        <li class="nav-item <?= $page_name == 'data-insert' ? 'active' : '' ?>">
          <a class="nav-link" href="<?= WEB_ROOT2 ?>/data-insert.php">文章新增</a>
        </li>

      </ul>
      <ul class="navbar-nav">
        <li class="nav-item <?= $page_name == 'login' ? 'active' : '' ?> ">
          <a class="nav-link" href="<?= WEB_ROOT2 ?>/social_media/social_media/login.php">登入</a>
        </li>
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