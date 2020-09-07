<style>
    #search_area {
        display: flex;
        align-items: center;
    }
</style>

<?php
$page_title = '商品列表';
$page_name = 'data_list';
require __DIR__ . '/parts/__connect_db.php';
// require __DIR__ . '/parts/__admin_required.php';

// 定義產出
$output = '';

$perPage = 5; // 每頁有幾筆資料
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$t_sql = "SELECT COUNT(1) FROM `shop_goods`";
$totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];
// die('~~~'); //exit; // 結束程式
$totalPages = ceil($totalRows / $perPage);

$rows = [];
if ($totalRows > 0) {
    if ($page < 1) {
        header('Location: data_list.php');
        exit;
    }
    if ($page > $totalPages) {
        header('Location: data_list.php?page=' . $totalPages);
        exit;
    };

    $sql = sprintf("SELECT * FROM `shop_goods` ORDER BY sid DESC LIMIT %s, %s", ($page - 1) * $perPage, $perPage);
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll();

    //類別資料表
    $c_sql = "SELECT * FROM shop_goods_type  ";
    $cates = $pdo->query($c_sql)->fetchAll();
}

// 搜尋
if (isset($_POST['search'])) {
    $searchq = $_POST['search'];
    $searchq = preg_replace("#[.]#i", "", $searchq);

    $sql =  ($_POST['search'] == '') ?  sprintf("SELECT * FROM `shop_goods` ORDER BY sid DESC LIMIT %s, %s", ($page - 1) * $perPage, $perPage) : "SELECT * FROM `shop_goods` WHERE name LIKE '%$searchq%'";


    $query = $pdo->prepare($sql);
    $query->execute();
    $count = $query->rowCount();

    $rows = $query->fetchAll();

    if ($count == 0) {
        $output = '沒有此商品喔!';
    } else {
        // while ($row = $query->fetch()) {
        //     $goods_name = $row['name'];
        //     // $category_sid_name = $row['category_sid'];
        //     $sid = $row['sid'];

        //     $output .= '<div>' . $goods_name . '</div>';
        // }
    }
}
// 搜尋結束
# 正規表示式
// https://developer.mozilla.org/zh-TW/docs/Web/JavaScript/Guide/Regular_Expressions
?>

<?php require __DIR__ . '/parts/__html_head.php'; ?>
<?php include __DIR__ . '/parts/__navbar.php'; ?>

<div class="container">
    <div class="row">

        <!-- 搜尋 -->
        <div>
            <form action="data_list.php" method="post" class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="text" name="search" placeholder="商品名稱搜尋" aria-label="Search">
                <button class="btn btn-outline-primary my-2 my-sm-0" type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>
        <!-- 搜尋結束 -->

        <!-- 印出搜尋結果 -->
        <div>
            <?php print($output); ?>
        </div>
        <!-- 印出搜尋結果結束 -->

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
        <!-- `sid`, `name`, `email`, `mobile`, `birthday`, `address`, `created_at` -->
        <thead>
            <tr>
                <?php if (isset($_SESSION['admin1'])) : ?>
                    <th scope="col"><i class="fas fa-trash-alt"></i></th>
                    <!-- <th scope="col"><i class="fas fa-user-times"></i></th> -->
                <?php endif; ?>

                <th scope="col">#</th>
                <th scope="col">商品名稱</th>
                <th scope="col" style="min-width: 100px;">類型</th>
                <th scope="col">品牌</th>
                <th scope="col">定價</th>
                <th scope="col">售價</th>
                <th scope="col">販售數量</th>
                <th scope="col">折扣</th>
                <th scope="col">收藏數</th>
                <th scope="col">建立時間</th>
                <th scope="col">上架</th>
                <?php if (isset($_SESSION['admin1'])) : ?>
                    <th scope="col"><i class="fas fa-edit"></i></th>
                <?php endif; ?>
                <th scope="col"><i class="fas fa-shopping-cart"></i></th>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($rows as $r) : ?>
                <tr>
                    <?php if (isset($_SESSION['admin1'])) : ?>
                        <td><a href="data_delete.php?sid=<?= $r['sid'] ?>" onclick="ifDel(event)" data-sid="<?= $r['sid'] ?>">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    <?php endif; ?>
                    <td><?= $r['sid'] ?></td>
                    <td><?= $r['name'] ?></td>

                    <td>
                        <?= $r['category_sid'] ?>
                        <?php foreach ($cates as $c) : ?>
                            <?= ($c['sid'] == $r['category_sid']) ? '.' . $c['name'] : '' ?>
                        <?php endforeach; ?>
                    </td>


                    <td><?= $r['brand'] ?></td>
                    <td><?= $r['pricing'] ?></td>
                    <td><?= $r['price'] ?></td>
                    <td><?= $r['sale'] ?></td>
                    <td><?= $r['discount'] ?></td>
                    <td><?= $r['heart'] ?></td>
                    <td><?= $r['created_at'] ?></td>

                    <!-- 上架 -->
                    <td>
                        <?php
                        $r['shelf_status'] == ['1'];
                        if ($r['shelf_status']) { //shelf_stat=1
                            echo "是\n";
                        } else { //shelf_stat!=1
                            echo "否\n";
                        }
                        ?>
                    </td>
                    <!-- 上架結束 -->


                    </td>

                    <?php if (isset($_SESSION['admin1'])) : ?>
                        <td><a href="data_edit.php?sid=<?= $r['sid'] ?>"><i class="fas fa-edit"></i></a></td>
                    <?php endif; ?>
                    <td><a href="http://localhost/phpCRUDProj/cart/petshop/3/data_list_test.php"><i class="fas fa-shopping-cart"></i></a></td>

                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>


<?php include __DIR__ . '/parts/__scripts.php'; ?>
<script>
    function ifDel(event) {
        const a = event.currentTarget;
        console.log(event.target, event.currentTarget);
        const sid = a.getAttribute('data-sid');
        if (!confirm(`是否要刪除編號為 ${sid} 的資料?`)) {
            event.preventDefault(); // 取消連往 href 的設定
        }
    }

    function delete_it(sid) {
        if (confirm(`是否要刪除編號為 ${sid} 的資料???`)) {
            location.href = 'data_delete.php?sid=' + sid;
        }
    }
</script>

<?php include __DIR__ . '/parts/__html_foot.php'; ?>