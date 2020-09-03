<?php
$page_title = '新增商品';
$page_name = 'data-insert';
require __DIR__ . '/parts/__connect_db.php';
require __DIR__ . '/parts/__admin_required.php';
?>
<?php require __DIR__ . '/parts/__html_head.php'; ?>
<style>
    span.red-stars {
        color: red;
    }

    small.error-msg {
        color: red;
    }
</style>
<?php include __DIR__ . '/parts/__navbar.php'; ?>
<div class="container">
    <div class="row">
        <div class="col-lg-6">
            <div id="infobar" class="alert alert-success" role="alert" style="display: none">
                A simple success alert—check it out!
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">新增領養資訊</h5>

                    <form name="form1" onsubmit="checkForm(); return false;" novalidate>
                        <div class="form-group">
                            <label for="name"><span class="red-stars">**</span> 名稱</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <small class="form-text error-msg"></small>
                        </div>

                        <!-- <div class="form-group">
                            <label for="dog_cat"><span class="red-stars">**</span> 貓咪/狗勾</label>
                            <input type="type" class="form-control" id="dog_cat" name="dog_cat">
                            <small class="form-text error-msg"></small>
                        </div> -->


                        <div class="form-group">
                            <label for="category_sid"><span class="red-stars">**</span> 貓咪/狗勾</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="Radio1" value="cat" name="dog_cat">
                                <label class="form-check-label" for="Radio1">貓咪</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="Radio2" value="dog" name="dog_cat">
                                <label class="form-check-label" for="Radio2">狗勾</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="age"><span class="red-stars">**</span> 年齡</label>
                            <input type="tel" class="form-control" id="age" name="age">
                            <small class="form-text error-msg"></small>
                        </div>
                        <div class="form-group">
                            <label for="area"><span class="red-stars">**</span> 所在地區</label>
                            <input type="tel" class="form-control" id="area" name="area">
                            <small class="form-text error-msg"></small>
                        </div>
                        <div class="form-group">
                            <label for="address"><span class="red-stars">**</span> 地址</label>
                            <input type="tel" class="form-control" id="address" name="address">
                            <small class="form-text error-msg"></small>
                        </div>
                        <div class="form-group">
                            <label for="description"><span class="red-stars">**</span> 介紹</label>
                            <input type="tel" class="form-control" id="description" name="description">
                            <small class="form-text error-msg"></small>
                        </div>

                        <button type="submit" class="btn btn-primary">確認</button>
                    </form>
                </div>
            </div>

        </div>
    </div>






</div>
<?php include __DIR__ . '/parts/__scripts.php'; ?>
<script>
    const $name = document.querySelector('#name');
    // const $type = document.querySelector('#dog_cat');
    const $brand = document.querySelector('#age');
    const $pricing = document.querySelector('#area');
    const $price = document.querySelector('#address');
    const $description = document.querySelector('#description');
    // const r_fields = [$name, $type, $brand, $pricing, $price, description];
    const r_fields = [$name, $brand, $pricing, $price, description];
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

            fetch('data-insert-api.php', {
                    method: 'POST',
                    body: fd
                })
                // .then(r => r.text())
                // .then(r => console.log(r))
                .then(r => r.json())
                .then(obj => {
                    console.log(obj);
                    if (obj.success) {
                        infobar.innerHTML = '新增成功';
                        infobar.className = "alert alert-success";
                        // if(infobar.classList.contains('alert-danger')){
                        //     infobar.classList.replace('alert-danger', 'alert-success')
                        // }
                        setTimeout(() => {
                            location.href = 'data-list.php';
                        }, 3000)
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
<?php include __DIR__ . '/parts/__html_foot.php'; ?>