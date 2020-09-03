<?php
$page_title = '商品列表';
$page_name = 'data-list';
require __DIR__ . '/__connect_db.php';

$perPage = 5; // 每頁有幾筆資料

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

$t_sql = "SELECT COUNT(1) FROM `shop_goods`";
$totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];
// die('~~~'); //exit; // 結束程式
$totalPages = ceil($totalRows / $perPage);

$rows = [];
if ($totalRows > 0) {
    if ($page < 1) {
        header('Location: data-list.php');
        exit;
    }
    if ($page > $totalPages) {
        header('Location: data-list.php?page=' . $totalPages);
        exit;
    };

    $sql = sprintf("SELECT * FROM `shop_goods` ORDER BY goods_id DESC LIMIT %s, %s", ($page - 1) * $perPage, $perPage);
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll();
}
# 正規表示式
// https://developer.mozilla.org/zh-TW/docs/Web/JavaScript/Guide/Regular_Expressions
?>
<?php require __DIR__ . '/__html_head.php'; ?>
<?php include __DIR__ . '/__navbar.php'; ?>
<div class="container">
    <div class="row">
        <div class="col d-flex justify-content-end">
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $page - 1 ?>">
                            <i class="fas fa-arrow-circle-left"></i>
                        </a>
                    </li>
                    <?php for ($i = $page - 3; $i <= $page + 3; $i++) :
                        if ($i < 1) continue;
                        if ($i > $totalPages) break;
                    ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $page == $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $page + 1 ?>">
                            <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>

        </div>
    </div>

    <form name="form1" onsubmit="checkForm(); return false;" novalidate>
        <table class="table table-striped">

            <thead>
                <tr>
                    <?php if (isset($_SESSION['admin'])) : ?>
                        <th scope="col"><i class="fas fa-trash-alt"></i></th>

                    <?php endif; ?>
                    <th scope="col">#</th>
                    <th scope="col">名稱</th>
                    <th scope="col">類型</th>
                    <th scope="col">品牌</th>
                    <th scope="col">定價</th>
                    <th scope="col">售價</th>
                    <th scope="col">販售數量</th>
                    <th scope="col">折扣</th>
                    <th scope="col">收藏數</th>
                    <th scope="col">建立時間</th>
                    <th scope="col">上架狀態</th>
                    <?php if (isset($_SESSION['admin'])) : ?>
                        <th scope="col"><i class="fas fa-edit"></i></th>
                    <?php endif; ?>

                    <th scope="col"><i class="fas fa-shopping-cart"></i></th>


                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $r) : ?>
                    <tr>
                        <?php if (isset($_SESSION['admin'])) : ?>
                            <td><a href="data-delete.php?goods_id=<?= $r['goods_id'] ?>" onclick="ifDel(event)" data-goods_id="<?= $r['goods_id'] ?>">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>

                        <?php endif; ?>
                        <td><?= $r['goods_id'] ?></td>
                        <td><?= $r['name'] ?></td>
                        <td><?= $r['type'] ?></td>
                        <td><?= $r['brand'] ?></td>
                        <td><?= $r['pricing'] ?></td>
                        <td><?= $r['price'] ?></td>
                        <td><?= $r['sale'] ?></td>
                        <td><?= $r['discount'] ?></td>
                        <td><?= $r['heart'] ?></td>
                        <td><?= $r['created_at'] ?></td>
                        <td><?= $r['shelf_status'] ?></td>

                        <?php if (isset($_SESSION['admin'])) : ?>
                            <td><a href="data-edit.php?goods_id=<?= $r['goods_id'] ?>"><i class="fas fa-edit"></i></a></td>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['admin'])) : ?>
                            <td><button type="submit" class="btn btn-primary"><i class="fas fa-shopping-cart"></i></button></td>
                        <?php endif; ?>


                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </form>

</div>
<?php include __DIR__ . '/__scripts.php'; ?>
<script>
    function ifDel(event) {
        const a = event.currentTarget;
        console.log(event.target, event.currentTarget);
        const goods_id = a.getAttribute('data-goods_id');
        if (!confirm(`是否要刪除編號為 ${goods_id} 的資料?`)) {
            event.preventDefault(); // 取消連往 href 的設定
        }
    }
</script>
<script>
    const submitBtn = document.querySelector('button[type=submit]');

    function checkForm() {
        let isPass = true;

        submitBtn.style.display = 'none';

        if (isPass) {
            const fd = new FormData(document.form1);

            fetch('cart_insert_api.php', {
                    method: 'POST',
                    body: fd
                })
                .then(r => r.json())
                .then(obj => {
                    console.log(obj);
                    if (obj.success) {
                        infobar.innerHTML = '新增成功';
                        infobar.className = "alert alert-success";

                        setTimeout(() => {
                            location.href = 'data-list.php';
                        }, 3000)
                    } else {
                        infobar.innerHTML = obj.error || '新增失敗';
                        infobar.className = "alert alert-danger";

                        submitBtn.style.display = 'block';
                    }
                    infobar.style.display = 'block';
                });

        } else {
            submitBtn.style.display = 'block';
        }
    }
</script>
<?php include __DIR__ . '/__html_foot.php'; ?>