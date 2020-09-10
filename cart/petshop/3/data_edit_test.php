<?php
$page_title = '編輯資料';
$page_name = 'data_edit_test';
require __DIR__ . '/__connect_db.php';
require __DIR__ . '/__admin_required.php';

$cart_id = isset($_GET['cart_id']) ? intval($_GET['cart_id']) : 0;
if (empty($cart_id)) {
    header('Location: data_list_test.php');
    exit;
}

$sql = " SELECT * FROM cart_list_01 WHERE cart_id=$cart_id";
$row = $pdo->query($sql)->fetch();
if (empty($row)) {
    header('Location: data_list_test.php');
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
                    <h5 class="card-title">編輯資料</h5>

                    <form name="form1" onsubmit="checkForm(); return false;" novalidate>
                        <input type="hidden" name="cart_id" value="<?= $row['cart_id'] ?>">
                        <div class="form-group">
                            <label for="name"><span class="red-stars">**</span> name</label>
                            <input type="text" readonly="readonly" class="form-control" id="name" name="name" required value="<?= htmlentities($row['name']) ?>">
                        </div>
                        <div class="form-group">
                            <label for="price"><span class="red-stars">**</span> price</label>
                            <input type="text" class="form-control" id="price" name="price" readonly="readonly" value="<?= htmlentities($row['price']) ?>">
                        </div>
                        <div class="form-group">
                            <label for="quantity"><span class="red-stars">**</span>quantity</label>

                            <input type="text" pattern="[1-9][0-9]*" class="form-control" id="quantity" name="quantity" onkeyup="return ValidateNumber(this,value)" value="<?= htmlentities($row['quantity']) ?>">
                            <small class="form-text error-msg"></small>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>

        </div>
    </div>






</div>
<?php include __DIR__ . '/__scripts.php'; ?>
<script>
    function ValidateNumber(e, pnumber) {
        if (!/^([1-9][0-9]*)$/.test(pnumber)) {
            e.value = /^([1-9][0-9]*)/.exec(e.value);
            //[1-9]* ^[1-9]*$ ^[1-9]* 09\d{2}-?\d{3}-?\d{3}
        }
        return false;
    }
</script>
<script>
    const infobar = document.querySelector('#infobar');
    const submitBtn = document.querySelector('button[type=submit]');
    const $quantity = document.querySelector('#quantity');
    const r_fields = [$quantity];

    function checkForm() {
        let isPass = true;

        r_fields.forEach(el => {
            el.style.borderColor = '#CCCCCC';
            el.nextElementSibling.innerHTML = '';
        });

        submitBtn.style.display = 'none';
        // submitBtn.style.display = 'block';
        // TODO: 檢查資料格式
        if ($quantity.value.length < 1) {
            isPass = false;
            $quantity.style.borderColor = 'red';
            $quantity.nextElementSibling.innerHTML = '請填寫正確的商品名稱';
        }

        if (isPass) {
            const fd = new FormData(document.form1);

            fetch('data_edit_api_test.php', {
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
                            location.href = '<?= $_SERVER['HTTP_REFERER'] ?? "data_list_test.php" ?>';
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