<?php
require __DIR__. '/__connect_db.php';
$pageName = 'product-list';
$qs = []; // query string
$perPage = 4;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$cate_id = isset($_GET['cate']) ? intval($_GET['cate']) : 0;
$search = isset($_GET['search']) ? $_GET['search'] : '';

$where = ' WHERE 1 ';
if($cate_id){
    $where .= " AND `category_sid`=$cate_id ";
    $qs['cate'] = $cate_id;
}
if($search){
    $search2 = $pdo->quote("%$search%");  // avoid SQL injection
    $where .= " AND (`author` LIKE $search2 OR `bookname` LIKE $search2 ) ";
}

$rows = [];
$totalPages = 0;
$t_sql = "SELECT COUNT(1) FROM `products` $where ";
$totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];

if($totalRows>0) {
    $totalPages = ceil($totalRows / $perPage);
    if ($page < 1) {
        header('Location: product-list.php');
        exit;
    }
    if ($page > $totalPages) {
        header('Location: product-list.php?page=' . $totalPages);
        exit;
    }
    $sql = sprintf("SELECT * FROM `products` %s LIMIT %s, %s", $where, ($page - 1) * $perPage, $perPage);
    $rows = $pdo->query($sql)->fetchAll();
}
// --- 分類資料
$c_sql = "SELECT * FROM `categories` WHERE `parent_sid`=0";
$cates = $pdo->query($c_sql)->fetchAll();

?>
<?php include __DIR__. '/__html_head.php' ?>
<style>
    .bookname{
        overflow : hidden;
        text-overflow : ellipsis;
        white-space : nowrap;
    }
</style>
<?php include __DIR__. '/__navbar.php' ?>
<div class="container">
<div class="row">
    <div class="col-lg-3">
        <div class="btn-group-vertical" style="width: 100%">
            <a type="button" class="btn btn-<?= $cate_id==0 ? '' : 'outline-' ?>primary" role="button" href="product-list.php">所有商品</a>
            <?php foreach($cates as $c): ?>
            <a type="button" class="btn btn-<?= $cate_id==$c['sid'] ? '' : 'outline-' ?>primary" role="button"
               href="?cate=<?= $c['sid'] ?>">
                <?= $c['name'] ?>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="col-lg-9">
        <div class="row">
            <div class="col d-flex justify-content-end">
                <form class="form-inline my-2 my-lg-0">
                    <?php if(!empty($cate_id)): ?>
                    <input type="hidden" name="cate" value="<?= $cate_id ?>">
                    <?php endif ?>
                    <input class="form-control mr-sm-2" type="search" placeholder="Search"
                           name="search" value="<?= htmlentities($search) ?>"
                           aria-label="Search">
                    <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <?php if($totalRows<1): ?>
                    <div class="alert alert-info" role="alert">
                        查詢不到資料
                    </div>
                <?php endif; ?>
                <nav aria-label="Page navigation example">
                    <ul class="pagination">
                        <?php for($i=1; $i<=$totalPages; $i++):
                            $qs['page'] = $i;
                            ?>
                        <li class="page-item <?= $page==$i ? 'active' : '' ?>">
                            <a class="page-link" href="?<?= http_build_query($qs) ?>"><?=$i?></a>
                        </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            </div>
        </div>
        <div class="row">
            <?php foreach($rows as $r): ?>
            <div class="col-lg-3 col-md-3 col-sm-4 p-item" data-sid="<?= $r['sid'] ?>">
                <div class="card">
                    <img src="imgs/small/<?= $r['book_id'] ?>.jpg" class="card-img-top" alt="">
                    <div class="card-body">
                        <p class="bookname"><?= $r['bookname'] ?></p>
                        <p class="bookname"><i class="fas fa-at"></i> <?= $r['author'] ?></p>
                        <p class="card-text"><i class="fas fa-dollar-sign"></i> <?= $r['price'] ?></p>
                        <p>
                            <select type="number" class="form-control" style="display: inline-block; width: auto;">
                                <?php for($i=1; $i<=20; $i++): ?>
                                <option value="<?=$i?>"><?=$i?></option>
                                <?php endfor; ?>
                            </select>
                            <button type="button" class="btn btn-primary buy-btn">敗</button>
                        </p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

    </div>
</div>



</div>
<?php include __DIR__. '/__scripts.php' ?>
<script>
    const buy_btns = $('.buy-btn');

    buy_btns.click(function(){
        const p_item = $(this).closest('.p-item');
        const sid = p_item.attr('data-sid');
        const qty = p_item.find('select').val();

        const sendObj = {
            action: 'add',
            sid,
            quantity: qty
        }
        $.get('handle-cart.php', sendObj, function(data){
            console.log(data);
            setCartCount(data);
        }, 'json');
    });


</script>


<?php require __DIR__. '/__html_foot.php' ?>




