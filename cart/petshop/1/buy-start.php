<?php
require __DIR__. '/__connect_db.php';
$pageName = 'buy-start';
if(empty($_SESSION['cart']) or empty($_SESSION['member'])){
    header('Location: product-list.php');
    exit;
}

// *** 抓到當下的價格資訊 *** begin
$sids = array_column($_SESSION['cart'], 'sid');
$sql = "SELECT * FROM `products` WHERE `sid` IN (". implode(',', $sids). ")";
$productData = [];
$stmt = $pdo->query($sql);
while($r = $stmt->fetch()){
    $productData[$r['sid']] = $r;
}
foreach ($_SESSION['cart'] as $k=>$v){
    $_SESSION['cart'][$k]['price'] = $productData[$v['sid']]['price'];
}
// *** 抓到當下的價格資訊 *** end

?>
<?php include __DIR__. '/__html_head.php' ?>
<?php include __DIR__. '/__navbar.php' ?>
<div class="container">

    <div class="row">
        <div class="col">
            <h2>訂單確認</h2>
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th scope="col">封面</th>
                    <th scope="col">書名</th>
                    <th scope="col">單價</th>
                    <th scope="col">數量</th>
                    <th scope="col">小計</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($_SESSION['cart'] as $i): ?>
                <tr class="p-item"
                    data-sid="<?= $i['sid'] ?>"
                    data-price="<?= $i['price'] ?>"
                    data-quantity="<?= $i['quantity'] ?>"
                >
                    <td>
                        <img src="imgs/small/<?= $i['book_id'] ?>.jpg">
                    </td>
                    <td><?= $i['bookname'] ?></td>
                    <td class="price"></td>
                    <td class="quantity"><?= $i['quantity'] ?></td>
                    <td class="sub-total"></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="alert alert-primary" role="alert">
                總計: <span id="total-price"></span>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col d-flex justify-content-between">

                <a type="button" class="btn btn-primary" role="button" href="cart.php">
                    <i class="fas fa-arrow-circle-left"></i> 回到購物車
                </a>

                <a type="button" class="btn btn-success" role="button" href="buy-finish.php">
                    <i class="fas fa-arrow-circle-right"></i> 確定購買
                </a>

        </div>
    </div>




</div>
<?php include __DIR__. '/__scripts.php' ?>
<script>
    const dallorCommas = function(n){
        return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    };

    function prepareCartTable() {
        const $p_items = $('.p-item');
        if(! $p_items.length && $('#total-price').length){
            // location.href = location.href;
            location.reload();
            return;
        }
        let total = 0;
        $p_items.each(function () {
            const sid = $(this).attr('data-sid');
            const price = $(this).attr('data-price');
            const quantity = $(this).attr('data-quantity');

            $(this).find('.price').text('$ ' + dallorCommas(price));
            $(this).find('.qty').val(quantity);
            $(this).find('.sub-total').text('$ ' + dallorCommas(quantity * price));
            total += quantity * price;
            $('#total-price').text('$ ' + dallorCommas(total));
        })
    }
    prepareCartTable();


</script>
<?php require __DIR__. '/__html_foot.php' ?>




