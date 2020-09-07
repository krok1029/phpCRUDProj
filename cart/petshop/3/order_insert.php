<?php
$page_title = '資料列表';
$page_name = 'order_insert';
require __DIR__ . '/__connect_db.php';

$perPage = 1000; // 每頁有幾筆資料

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

$t_sql = "SELECT COUNT(1) FROM `cart_list_01`";
$totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];

// die('~~~'); //exit; // 結束程式

$totalPages = ceil($totalRows / $perPage);
$total_price = 0;
$rows = [];
if ($totalRows > 0) {
    if ($page < 1) {
        header('Location: order_insert.php');
        exit;
    }
    if ($page > $totalPages) {
        header('Location: order_insert.php?page=' . $totalPages);
        exit;
    };

    $sql = sprintf("SELECT * FROM `cart_list_01` ORDER BY cart_id DESC LIMIT %s, %s", ($page - 1) * $perPage, $perPage);
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll();
}

// $row = [];

// $sql2 = " SELECT * FROM `pet_shop_admins` ORDER BY  admins_id";
// $row = $pdo->query($sql2)->fetch();

$admins_id = isset($_GET['admins_id']) ? intval($_GET['admins_id']) : 0;
if (empty($admins_id)) {
    header('Location: data_list_test.php');
    exit;
}

$sql2 = " SELECT * FROM pet_shop_admins WHERE admins_id=$admins_id";
$row = $pdo->query($sql2)->fetch();
if (empty($row)) {
    header('Location: data_list_test.php');
    exit;
}
?>

<?php require __DIR__ . '/__html_head.php'; ?>
<?php include __DIR__ . '/__navbar.php'; ?>

<style>
    span.red-stars {
        color: red;
    }

    small.error-msg {
        color: red;
    }
</style>

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

        <div class="d-flex">

            <div class="col-6">
                <div>
                    <div id="infobar" class="alert alert-success" role="alert" style="display: none">
                        A simple success alert—check it out!
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">填寫訂單資料</h5>

                            <!-- <form name="form" onsubmit="checkForm(); return false;" novalidate> -->
                            <input type="hidden" name="admins_id" value="<?= $row['admins_id'] ?>">
                            <div class="form-group">
                                <label for="nickname"><span class="red-stars">**</span> 收件人名稱</label>
                                <input type="text" class="form-control" id="nickname" name="nickname" required value="<?= htmlentities($row['nickname']) ?>">
                                <small class="form-text error-msg"></small>
                            </div>
                            <div class="form-group">
                                <label for="address"><span class="red-stars">**</span> 收件地址</label>
                                <input type="text" class="form-control" id="address" name="address" value="<?= htmlentities($row['address']) ?>">
                                <small class="form-text error-msg"></small>
                            </div>
                            <div class="form-group">
                                <label for="cellphone"><span class="red-stars">**</span> 連絡電話</label>
                                <input type="tel" class="form-control" id="cellphone" name="cellphone" value="<?= htmlentities($row['cellphone']) ?>" pattern="09\d{2}-?\d{3}-?\d{3}">
                                <small class="form-text error-msg"></small>
                            </div>
                            <button type="submit" class="btn btn-primary">確認</button>
                            <!-- </form> -->
                        </div>
                    </div>

                </div>
            </div>

            <div class="col-6">

                <table class="table table-striped">
                    <!-- `cart_id`, `name`, `price`, `quantity` -->
                    <thead>
                        <tr>
                            <th scope="col">商品</th>
                            <th scope="col">價格</th>
                            <th scope="col">數量</th>
                            <th scope="col">小計</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($rows as $r) : ?>
                            <tr>
                                <td><?= $r['name'] ?></td>
                                <td><?= $r['price'] ?></td>
                                <td><?= $r['quantity'] ?></td>
                                <td><?= $r['price'] * $r['quantity'] ?></td>
                                <?php $total_price = $total_price + ($r['price'] * $r['quantity']) ?>
                            </tr>

                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="form-group">
                    <label for="total_price"><span class="red-stars">**</span> total:</label>
                    <input type="text" class="form-control" id="total_price" name="total_price" readonly="readonly" value="<?= htmlentities($total_price) ?>">
                    <small class="form-text error-msg"></small>
                </div>
            </div>

        </div>

    </div>
</form>

<?php include __DIR__ . '/__scripts.php'; ?>
<script>
    const cellphone_pattern = /^09\d{2}-?\d{3}-?\d{3}$/;
    const $nickname = document.querySelector('#nickname');
    const $address = document.querySelector('#address');
    const $cellphone = document.querySelector('#cellphone');

    const r_fields = [$nickname, $address, $cellphone];
    const infobar = document.querySelector('#infobar');
    const submitBtn = document.querySelector('button[type=submit]');

    function checkForm() {
        let isPass = true;

        r_fields.forEach(el => {
            el.style.borderColor = '#CCCCCC';
            el.nextElementSibling.innerHTML = '';
        });
        submitBtn.style.display = 'none';
        // TODO: 檢查資料格式
        if ($nickname.value.length < 2) {
            isPass = false;
            $nickname.style.borderColor = 'red';
            $nickname.nextElementSibling.innerHTML = '請填寫正確的收件人名稱';
        }
        if ($address.value.length < 5) {
            isPass = false;
            $address.style.borderColor = 'red';
            $address.nextElementSibling.innerHTML = '請填寫正確的收件地址';
        }
        if (!cellphone_pattern.test($cellphone.value)) {
            isPass = false;
            $cellphone.style.borderColor = 'red';
            $cellphone.nextElementSibling.innerHTML = '請填寫正確的格式';
        }

        if (isPass) {
            const fd = new FormData(document.form1);

            fetch('order_insert_api.php', {
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