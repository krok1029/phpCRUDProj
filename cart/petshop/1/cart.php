<?php
require __DIR__. '/__connect_db.php';
$pageName = 'cart';
?>
<?php include __DIR__. '/__html_head.php' ?>
<?php include __DIR__. '/__navbar.php' ?>
<div class="container">
    <?php if(empty($_SESSION['cart'])): ?>
    <div class="row">
        <div class="col">
            <div class="alert alert-danger" role="alert">
                購物車內沒有商品
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="row">
        <div class="col">
            <table class="table table-striped table-bordered">
                <thead>
                <tr>
                    <th scope="col"><i class="fas fa-trash-alt"></i></th>
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
                        <a href="javascript:" class="remove-item"><i class="fas fa-trash-alt"></i></a>
                    </td>
                    <td>
                        <img src="imgs/small/<?= $i['book_id'] ?>.jpg">
                    </td>
                    <td><?= $i['bookname'] ?></td>
                    <td class="price"></td>
                    <td class="quantity">
                        <select class="form-control qty">
                            <?php for($i=1; $i<=20; $i++): ?>
                                <option value="<?=$i?>"><?=$i?></option>
                            <?php endfor; ?>
                        </select>
                    </td>
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
        <div class="col d-flex flex-row-reverse bd-highlight">
            <?php if(isset($_SESSION['member'])): ?>
                <a type="button" class="btn btn-success" role="button" href="buy-start.php">結帳</a>
            <?php else: ?>
                <a type="button" class="btn btn-danger" role="button" href="login.php">請先登入再結帳</a>
            <?php endif; ?>

        </div>
    </div>
    <?php endif; ?>



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


    const qty_sel = $('.qty');
    qty_sel.on('change', function(){
        const p_item = $(this).closest('.p-item');
        const sid = p_item.attr('data-sid');
        // alert(sid +', '+ $(this).val() )
        const sendObj = {
            action: 'add',
            sid: sid,
            quantity: $(this).val()
        }
        $.get('handle-cart.php', sendObj, function(data){
            setCartCount(data); // navbar
            p_item.attr('data-quantity', sendObj.quantity);
            prepareCartTable();
        }, 'json');
    });

    $('.remove-item').on('click', function(){
        const p_item = $(this).closest('.p-item');
        const sid = p_item.attr('data-sid');
        $.get('handle-cart.php', {action: 'remove', sid:sid }, function(data){
            setCartCount(data); // navbar
            p_item.remove();
            prepareCartTable();
        }, 'json');

    });
</script>
<?php require __DIR__. '/__html_foot.php' ?>




