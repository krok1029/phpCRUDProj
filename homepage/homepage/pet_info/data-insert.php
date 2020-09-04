<?php
$page_title = '新增';
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
                            <label for="user_id"><span class="red-stars">**</span> 會員</label>
                            <input type="text" class="form-control" id="user_id" name="user_id" required>
                            <small class="form-text error-msg"></small>
                        </div>

                        <div class="form-group">
                            <label for="dog_cat"><span class="red-stars">**</span> 貓/狗</label>
                            <input type="type" class="form-control" id="dog_cat" name="dog_cat" required>
                            <small class="form-text error-msg"></small>
                        </div>


                        <!-- <div class="form-group">
                            <label for="category_sid"><span class="red-stars">**</span> 貓咪/狗勾</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="Radio1" value="cat" name="dog_cat">
                                <label class="form-check-label" for="Radio1">貓咪</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="Radio2" value="dog" name="dog_cat">
                                <label class="form-check-label" for="Radio2">狗勾</label>
                            </div>
                        </div> -->

                        <div class="form-group">
                            <label for="age"><span class="red-stars">**</span> 年齡</label>
                            <input type="tel" class="form-control" id="age" name="age">
                            <small class="form-text error-msg"></small>
                        </div>
                        <div class="form-group">
                            <label for="area"><span class="red-stars">**</span> 地區</label>
                            <input type="tel" class="form-control" id="area" name="area">
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
    const $user_id = document.querySelector('#user_id');
    const $dog_cat = document.querySelector('#dog_cat');
    const $age = document.querySelector('#age');
    const $area = document.querySelector('#area');
    const r_fields = [$user_id, $dog_cat, $age, $area];
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