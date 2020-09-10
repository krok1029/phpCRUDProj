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

    $sql = "UPDATE `cart_list_01` SET `buy_now` = 0 WHERE buy_now = 1";
    $stmt = $pdo->query($sql);
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
                    <?php if (isset($_SESSION['admin1'])) : ?>
                        <th scope="col"><i class="fas fa-trash-alt"></i></th>
                    <?php endif; ?>
                    <!-- <th scope="col"><i class="fas fa-user-times"></i></th> -->
                    <th scope="col">check</th>
                    <th scope="col">商品</th>
                    <th scope="col">價格</th>
                    <th scope="col">數量</th>
                    <th scope="col">小計</th>
                    <?php if (isset($_SESSION['admin1'])) : ?>
                        <th scope="col"><i class="fas fa-edit"></i></th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $r) : ?>
                    <tr>
                        <?php if (empty($r['is_buy'])) : ?>
                            <?php if (isset($_SESSION['admin1'])) : ?>
                                <td><a href="data_delete_test.php?cart_id=<?= $r['cart_id'] ?>" onclick="ifDel(event)" data-cart_id="<?= $r['cart_id'] ?>" data-name="<?= $r['name'] ?>">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            <?php endif; ?>
                            <td><Input type="Checkbox" name="cart_id_check[]" value="<?= $r['cart_id'] ?>"></td>

                            <input type="hidden" name="cart_id" value="<?= $r['cart_id'] ?>">
                            <td><?= $r['name'] ?></td>
                            <td><?= $r['price'] ?></td>

                            <td>
                                <button class="btn btn-primary add_quantity" data-goods_id1="<?= $r['goods_id'] ?>" data-quantity1="<?= $r['quantity'] ?>">
                                    +
                                </button>
                                <span style="display:inline-block;width:30px;text-align:center;">
                                    <?= $r['quantity']  ?>
                                </span>

                                <button class="btn btn-primary minus_quantity" data-goods_id2="<?= $r['goods_id'] ?>" data-quantity2="<?= $r['quantity'] ?>">
                                    -
                                </button>
                            </td>
                            <td class="subtotal"></td>

                            <?php $totalPrice = $totalPrice + ($r['price'] * $r['quantity']) ?>
                            <?php if (isset($_SESSION['admin1'])) : ?>
                                <td><a href="data_edit_test.php?cart_id=<?= $r['cart_id'] ?>"><i class="fas fa-edit"></i></a></td>
                            <?php endif; ?>
                        <?php endif; ?>
                    </tr>

                <?php endforeach; ?>
            </tbody>
        </table>
        <div>
            <h1 class="totalPrice">total:</h1>
            <?php if ($totalPrice != 0) : ?>
                <button type="submit" class="btn btn-primary">buy</button>
            <?php endif; ?>
        </div>
    </div>


</form>

<?php include __DIR__ . '/parts/__scripts.php'; ?>
<script>
    function ifDel(event) {
        const a = event.currentTarget;
        console.log(event.target, event.currentTarget);
        const cart_id = a.getAttribute('data-cart_id');
        const name = a.getAttribute('data-name');
        if (!confirm(`是否要刪除 ${cart_id}-${name} ?`)) {
            event.preventDefault(); // 取消連往 href 的設定
        }
    }

    const submitBtn = document.querySelector('button[type=submit]');

    function checkForm() {


        submitBtn.style.display = 'none';

        setTimeout(() => {
            location.href = 'order_insert.php';
        }, 1000)



    }


    ///////////////////

    $('.add_quantity').on('click', function(event) {
        event.preventDefault();
        console.log('add goods id:', $(this).data('goods_id1'));

        let goods_id1 = $(this).data('goods_id1');
        let quantity1 = $(this).data('quantity1');

        console.log('add quantity:', quantity1);

        fetch(`cart_update_add.php?goods_id=${goods_id1}&quantity=${quantity1}`, {
                method: 'GET',
            })
            .then(() => {
                let newAddQuantity = $(this).next().text() * 1 + 1;
                $(this).next().text(newAddQuantity);
                $(this).data('quantity1', newAddQuantity);
                $(this).next().next().data('quantity2', newAddQuantity);
                checkNumber();
                console.log($(this).next().text());
            });
    });

    ///////////

    $('.minus_quantity').on('click', function(event) {
        event.preventDefault();
        console.log('minus goods id:', $(this).data('goods_id2'));

        let goods_id2 = $(this).data('goods_id2');
        let quantity2 = $(this).data('quantity2');

        console.log('minus quantity:', quantity2);

        fetch(`cart_update_minus.php?goods_id=${goods_id2}&quantity=${quantity2}`, {
                method: 'GET',
            })
            .then(() => {
                let newMinusQuantity = $(this).prev().text() * 1 - 1;
                $(this).prev().text(newMinusQuantity);
                $(this).data('quantity2', newMinusQuantity);
                $(this).prev().prev().data('quantity1', newMinusQuantity);
                checkNumber();
                console.log($(this).next().text());
            });
    });

    ///////////////////

    $('input:checkbox').on('change', function() {
        console.log($(this).prop('checked'));
        console.log($(this).val());
        const fd = new FormData();
        fd.append('cart_id', $(this).val())
        if ($(this).prop('checked')) {
            console.log('checked');
            fd.append('buy_now', 1);
        } else {
            console.log('unchecked');
            fd.append('buy_now', 0);
        }

        fetch('data_list_api_test.php', {
                method: 'POST',
                body: fd
            })
            .then(r => r.json())
            .then(obj => {
                console.log(obj);
            });
        checkNumber();
    })

    function checkNumber() {

        $('.minus_quantity').each(function() {
            if ($(this).data('quantity2') === 1) {
                $(this).attr('disabled', true);
            } else {
                $(this).attr('disabled', false);
            }
        });

        $('.subtotal').each(function() {
            $(this).text($(this).prev().find('span').text() * $(this).prev().prev().text())
        })
        let total = 0;
        submitBtn.style.display = 'none';
        $('input:checkbox').each(function() {
            if ($(this).prop('checked')) {
                total += +$(this).parent().siblings().eq(5).text();
                submitBtn.style.display = 'block';
            }
        })

        $('.totalPrice').text('total:' + total)
    }

    $(document).ready(function() {
        checkNumber();
    })
    ///
</script>
<?php include __DIR__ . '/parts/__html_foot.php'; ?>