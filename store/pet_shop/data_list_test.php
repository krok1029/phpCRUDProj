<?php
$page_title = '資料列表';
$page_name = 'data_list_test';
require __DIR__ . '/parts/__connect_db.php';

$perPage = 1000; // 每頁有幾筆資料

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

$t_sql = "SELECT COUNT(1) FROM `cart_list_01`";
$totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];
// die('~~~'); //exit; // 結束程式
$totalPages = ceil($totalRows / $perPage);
$totalPrice = 0;
$rows = [];
if ($totalRows > 0) {
    if ($page < 1) {
        header('Location: data_list_test.php');
        exit;
    }
    if ($page > $totalPages) {
        header('Location: data_list_test.php?page=' . $totalPages);
        exit;
    };

    $sql = sprintf("SELECT * FROM `cart_list_01` ORDER BY cart_id DESC LIMIT %s, %s", ($page - 1) * $perPage, $perPage);
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll();
}

// $alltotal = "SELECT SUM(total) FROM `cart_list_01`";

# 正規表示式
// https://developer.mozilla.org/zh-TW/docs/Web/JavaScript/Guide/Regular_Expressions
?>
<?php require __DIR__ . '/parts/__html_head.php'; ?>
<?php include __DIR__ . '/parts/__navbar.php'; ?>

<form name="form1" onsubmit="checkForm(); return false;" novalidate>
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

        <table class="table table-striped">
            <!-- `cart_id`, `name`, `price`, `quantity` -->
            <thead>
                <tr>
                    <?php if (isset($_SESSION['admin'])) : ?>
                        <th scope="col"><i class="fas fa-trash-alt"></i></th>
                    <?php endif; ?>
                    <!-- <th scope="col"><i class="fas fa-user-times"></i></th> -->
                    <th scope="col">商品</th>
                    <th scope="col">價格</th>
                    <th scope="col">數量</th>
                    <th scope="col">小計</th>
                    <?php if (isset($_SESSION['admin'])) : ?>
                        <th scope="col"><i class="fas fa-edit"></i></th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $r) : ?>
                    <tr>
                        <?php if (isset($_SESSION['admin'])) : ?>
                            <td><a href="data_delete_test.php?cart_id=<?= $r['cart_id'] ?>" onclick="ifDel(event)" data-cart_id="<?= $r['cart_id'] ?>">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>

                        <?php endif; ?>
                        <td><?= $r['name'] ?></td>
                        <td><?= $r['price'] ?></td>
                        <td><?= $r['quantity'] ?></td>
                        <td><?= $r['price'] * $r['quantity'] ?></td>
                        <?php $totalPrice = $totalPrice + ($r['price'] * $r['quantity']) ?>
                        <?php if (isset($_SESSION['admin'])) : ?>
                            <td><a href="data_edit_test.php?cart_id=<?= $r['cart_id'] ?>"><i class="fas fa-edit"></i></a></td>
                        <?php endif; ?>
                    </tr>

                <?php endforeach; ?>
            </tbody>
        </table>
        <div>
            <h1>total:<?= $totalPrice ?></h1>
            <a class="btn btn-primary" href="order_insert.php" role="button">Submit</a>
        </div>
    </div>


</form>

<?php include __DIR__ . '/parts/__scripts.php'; ?>
<script>
    function ifDel(event) {
        const a = event.currentTarget;
        console.log(event.target, event.currentTarget);
        const cart_id = a.getAttribute('data-cart_id');
        if (!confirm(`是否要刪除 ${name} ?`)) {
            event.preventDefault(); // 取消連往 href 的設定
        }
    }
</script>
<?php include __DIR__ . '/parts/__html_foot.php'; ?>