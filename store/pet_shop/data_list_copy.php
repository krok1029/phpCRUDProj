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

$perPage = 5; // 每頁有幾筆資料
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$t_sql = "SELECT COUNT(1) FROM `shop_goods`";
$totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];
// die('~~~'); //exit; // 結束程式
$totalPages = ceil($totalRows / $perPage);

//拿到商品表
$shop_goods_sql = "SELECT `sid`, `name`, `category_sid`, 
`brand`, `pricing`, `price` FROM `shop_goods`";
$shop_goods_data = $pdo->query($shop_goods_sql)->fetchAll();

//拿到名稱資料

$data = [];


//搜尋篩選
if (isset($_GET['name'])) {
    if ($totalRows > 0) {
        if ($page < 1) $page = 1;
        if ($page > $totalPage) $page = $totalPage;
        $num = null;
        foreach ($shop_goods_data as $goods) {
            if ($goods['name'] == $_GET['name']) {
                $num = $goods['sid'];
            };
        };
        $sql = sprintf("SELECT * FROM `shop_goods` WHERE `sid`= $num  LIMIT %s, %s", ($page - 1) * $perPage, $perPage);
        $statement = $pdo->query($sql);
        $data = $statement->fetchAll();
    };
};

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
# 正規表示式
// https://developer.mozilla.org/zh-TW/docs/Web/JavaScript/Guide/Regular_Expressions
?>

<?php require __DIR__ . '/parts/__html_head.php'; ?>
<?php include __DIR__ . '/parts/__navbar.php'; ?>

<div class="container">
    <div class="row">

        <!-- 搜尋 -->
        <div id="search_area">
            <input class="form-control" type="text" placeholder="請輸入商品名稱" id="search_input">
            <button type="button" class="btn btn-info" id="search_btn"><i class="fas fa-search"></i></button>
        </div>

        <!-- <div>
            <form class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
        </div> -->
        <!-- 搜尋結束 -->

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
                <th scope="col">類型</th>
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
                    <td><a href="#"><i class="fas fa-shopping-cart"></i></a></td>

                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="modal fade" id="no_result" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">篩選結果</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" role="alert">
                    無篩選結果
                </div>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/parts/__scripts.php'; ?>
<script>
    const search_input = document.querySelector('#search_input');
    const search_btn = document.querySelector('#search_btn');
    // const shop_goods_sid_select = document.querySelector('#shop_goods_sid_select');
    const no_result = document.querySelector('#no_result');


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

    //篩選功能
    search_input.addEventListener('change', () => {
        if (search_input.value) {
            location.href = `data_list.php?shop_goods_name=${search_input.value}`;
        };
    });
    search_btn.addEventListener('click', () => {
        location.href = `data_list.php?name=${search_input.value.trim()}`;
    });


    //跳出搜尋無資料
    if (!document.querySelector('tbody').innerText) {
        $('#no_result').modal('show');
    };
</script>
<?php include __DIR__ . '/parts/__html_foot.php'; ?>