<?php
$page_title = '編輯商品';
$page_name = 'data-edit';
require __DIR__ . '/__connect_db.php';
require __DIR__ . '/__admin_required.php';
$goods_id = isset($_GET['goods_id']) ? intval($_GET['goods_id']) : 0;
if (empty($goods_id)) {
    header('Location: data-list.php');
    exit;
}

$sql = " SELECT * FROM shop_goods WHERE goods_id=$goods_id";
$row = $pdo->query($sql)->fetch();
if (empty($row)) {
    header('Location: data-list.php');
    exit;
}


?>
<?php require __DIR__ . '/__html_head.php'; ?>
<style>
    span.red-stars {
        color: red;
    }

    small.error-msg {
        color: red;
    }
</style>
<?php include __DIR__ . '/__navbar.php'; ?>
<div class="container">
    <div class="row">
        <div class="col-lg-6">
            <div id="infobar" class="alert alert-success" role="alert" style="display: none">
                A simple success alert—check it out!
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">編輯商品</h5>

                    <form name="form1" onsubmit="checkForm(); return false;" novalidate>
                        <input type="hidden" name="goods_id" value="<?= $row['goods_id'] ?>">
                        <div class="form-group">
                            <label for="name"><span class="red-stars">**</span> 名稱</label>
                            <input type="text" class="form-control" id="name" name="name" required value="<?= htmlentities($row['name']) ?>">
                            <small class="form-text error-msg"></small>
                        </div>
                        <div class="form-group">
                            <label for="type"><span class="red-stars">**</span> 類型</label>
                            <input type="type" class="form-control" id="type" name="type" value="<?= htmlentities($row['type']) ?>">
                            <small class="form-text error-msg"></small>
                        </div>
                        <div class="form-group">
                            <label for="brand"><span class="red-stars">**</span> 品牌</label>
                            <input type="tel" class="form-control" id="brand" name="brand" value="<?= htmlentities($row['brand']) ?>">
                            <small class="form-text error-msg"></small>
                        </div>
                        <div class="form-group">
                            <label for="pricing"><span class="red-stars">**</span> 定價</label>
                            <input type="tel" class="form-control" id="pricing" name="pricing" value="<?= htmlentities($row['pricing']) ?>">
                            <small class="form-text error-msg"></small>
                        </div>
                        <div class="form-group">
                            <label for="price"><span class="red-stars">**</span> 售價</label>
                            <input type="tel" class="form-control" id="price" name="price" value="<?= htmlentities($row['price']) ?>">
                            <small class="form-text error-msg"></small>
                        </div>

                        <button type="submit" class="btn btn-primary">確認</button>
                    </form>
                </div>
            </div>

        </div>
    </div>






</div>
<?php include __DIR__ . '/__scripts.php'; ?>
<script>
    const $name = document.querySelector('#name');
    const $type = document.querySelector('#type');
    const $brand = document.querySelector('#brand');
    const $pricing = document.querySelector('#pricing');
    const $price = document.querySelector('#price');
    const r_fields = [$name, $type, $brand, $pricing, $price];
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
        if ($name.value.length < 2) {
            isPass = false;
            $name.style.borderColor = 'red';
            $name.nextElementSibling.innerHTML = '請填寫正確的商品名稱';
        }

        if (isPass) {
            const fd = new FormData(document.form1);

            fetch('data-edit-api.php', {
                    method: 'POST',
                    body: fd
                })
                .then(r => r.json())
                .then(obj => {
                    console.log(obj);
                    if (obj.success) {
                        infobar.innerHTML = '修改成功';
                        infobar.className = "alert alert-success";

                        setTimeout(() => {
                            location.href = '<?= $_SERVER['HTTP_REFERER'] ?? "data-list.php" ?>';
                        }, 3000)

                    } else {
                        infobar.innerHTML = obj.error || '資料沒有修改';
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