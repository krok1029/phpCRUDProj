<?php
$page_title = '新增商品';
$page_name = 'data_insert';
require __DIR__. '/parts/__connect_db.php';
require __DIR__. '/parts/__admin_required.php';
?>

<!-- 類別 -->
<?php 


$c_sql = "SELECT * FROM shop_goods_type";

$cates = $pdo->query($c_sql)->fetchAll();
?>
<!-- 類別結束 -->
<?php require __DIR__. '/parts/__html_head.php'; ?>
<style>
    span.red-stars {
        color: red;
    }
    small.error-msg {
        color: red;
    }
</style>
<?php include __DIR__. '/parts/__navbar.php'; ?>
<div class="container">
    <div class="row">
        <div class="col-lg-6">
            <div id="infobar" class="alert alert-success" role="alert" style="display: none">
                A simple success alert—check it out!
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">新增商品</h5>

                    <form name="form1" onsubmit="checkForm(); return false;" novalidate>
                        <div class="form-group">
                            <label for="name"><span class="red-stars">**</span> 名稱</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <small class="form-text error-msg"></small>
                        </div>
                        

                        <div class="form-group">
                            <label for="category_sid">類型</label>
                            <select class="form-control" id="category_sid" name="category_sid" data-val="<?= $row['category_sid'] ?>">
                                <?php foreach ($cates as $c) : ?>
                                    <option value="<?= $c['sid'] ?>"><?= $c['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small class="form-text error-msg"></small>
                        </div>


                        <!-- <div class="form-group">
                            <label for="category_sid"><span class="red-stars">**</span> 類型</label>
                            <input type="category_sid" class="form-control" id="category_sid" name="category_sid">
                            <small class="form-text error-msg"></small>
                        </div> -->
                        
                        <div class="form-group">
                            <label for="brand"><span class="red-stars">**</span> 品牌</label>
                            <input type="tel" class="form-control" id="brand" name="brand">
                            <small class="form-text error-msg"></small>
                        </div>
                        <div class="form-group">
                            <label for="pricing"><span class="red-stars">**</span> 定價</label>
                            <input type="tel" class="form-control" id="pricing" name="pricing">
                            <small class="form-text error-msg"></small>
                        </div>
                        <div class="form-group">
                            <label for="price"><span class="red-stars">**</span> 售價</label>
                            <input type="tel" class="form-control" id="price" name="price">
                            <small class="form-text error-msg"></small>
                        </div>

                        <button type="submit" class="btn btn-primary">確認</button>
                    </form>
                </div>
            </div>

        </div>
    </div>






</div>
<?php include __DIR__. '/parts/__scripts.php'; ?>
<script>
    const $name = document.querySelector('#name');
    const $category_sid = document.querySelector('#category_sid');
    let val = $category_sid.getAttribute('data-val');
    $category_sid.value = val;
    document.form1.category_sid.value = val;
    // const $type = document.querySelector('#type');
    const $brand = document.querySelector('#brand');
    const $pricing = document.querySelector('#pricing');
    const $price = document.querySelector('#price');
    const r_fields = [$name, $category_sid, $brand, $pricing, $price];
    const infobar = document.querySelector('#infobar');
    const submitBtn = document.querySelector('button[type=submit]');

    function checkForm(){
        let isPass = true;

        r_fields.forEach(el=>{
            el.style.borderColor = '#CCCCCC';
            el.nextElementSibling.innerHTML = '';
        });
        submitBtn.style.display = 'none';
        // TODO: 檢查資料格式
        if($name.value.length < 2) {
            isPass = false;
            $name.style.borderColor = 'red';
            $name.nextElementSibling.innerHTML = '請填寫正確的商品名稱';
        }

        if(isPass) {
            const fd = new FormData(document.form1);

            fetch('data_insert_api.php', {
                method: 'POST',
                body: fd
            })
                .then(r => r.json())
                .then(obj => {
                    console.log(obj);
                    if(obj.success){
                        infobar.innerHTML = '新增成功';
                        infobar.className = "alert alert-success";
                        // if(infobar.classList.contains('alert-danger')){
                        //     infobar.classList.replace('alert-danger', 'alert-success')
                        // }
                        setTimeout(()=>{
                            location.href = 'data_list.php';
                        }, 1000)
                    } else {
                        infobar.innerHTML = obj.error || '新增失敗';
                        infobar.className = "alert alert-danger";
                        // if(infobar.classList.contains('alert-success')){
                        //     infobar.classList.replace('alert-success', 'alert-danger')
                        // }
                        submitBtn.style.display = 'block';
                    }
                    infobar.style.display = 'block';
                });

        } else {
            submitBtn.style.display = 'block';
        }
    }
</script>
<?php include __DIR__. '/parts/__html_foot.php'; ?>