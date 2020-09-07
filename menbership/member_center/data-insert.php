<?php
$page_title = '新增資料';
$page_name = 'data-insert';
require __DIR__ . '/parts/__connect_db.php';
//新增未登入的功能限制
// require __DIR__ . '/parts/__admin_required.php';
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
                    <h5 class="card-title">新增資料</h5>

                    <form name="form1" onsubmit="checkForm(); return false;" novalidate>
                        <div class="form-group">
                            <label for="name"><span class="red-stars">**</span> 姓名</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                            <small class="form-text error-msg"></small>
                        </div>
                        <div class="form-group">
                            <label for="birthday">出生年月日</label>
                            <input type="date" class="form-control" id="birthday" name="birthday">
                        </div>
                        <div class="form-group">
                            <label for="telephone"><span class="red-stars">**</span> 連絡電話(格式：xx-xxxx-xxxx)</label>
                            <input type="tel" class="form-control" id="telephone" name="telephone" pattern="\d{2}-\d{4}-\d{4}">
                            <small class="form-text error-msg"></small>
                        </div>
                        <div class="form-group">
                            <label for="mobile"><span class="red-stars">**</span> 行動電話</label>
                            <input type="tel" class="form-control" id="mobile" name="mobile" pattern="09\d{2}-?\d{3}-?\d{3}">
                            <small class="form-text error-msg"></small>
                        </div>
                        <div class="form-group">
                            <label for="email"><span class="red-stars">**</span> 電子信箱</label>
                            <input type="email" class="form-control" id="email" name="email">
                            <small class="form-text error-msg"></small>
                        </div>
                        <div class="form-group">
                            <label for="address">連絡地址</label>
                            <textarea class="form-control" name="address" id="address" cols="30" rows="3"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>

        </div>
    </div>






</div>
<?php include __DIR__ . '/parts/__scripts.php'; ?>
<script>
    const email_pattern = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
    const telephone_pattern = /^\d{2}-\d{4}-\d{4}$/;
    const mobile_pattern = /^09\d{2}-?\d{3}-?\d{3}$/;
    const $name = document.querySelector('#name');
    const $email = document.querySelector('#email');
    const $telephone = document.querySelector('#telephone');
    const $mobile = document.querySelector('#mobile');
    const r_fields = [$name, $email, $telephone, $mobile];
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
            $name.nextElementSibling.innerHTML = '請填寫正確的姓名';
        }

        if (!email_pattern.test($email.value)) {
            isPass = false;
            $email.style.borderColor = 'red';
            $email.nextElementSibling.innerHTML = '請填寫正確格式的電子信箱';
        }

        if (!telephone_pattern.test($telephone.value)) {
            isPass = false;
            $telephone.style.borderColor = 'red';
            $telephone.nextElementSibling.innerHTML = '請填寫正確的連絡電話';
        }

        if (!mobile_pattern.test($mobile.value)) {
            isPass = false;
            $mobile.style.borderColor = 'red';
            $mobile.nextElementSibling.innerHTML = '請填寫正確的行動電話';
        }

        if (isPass) {
            const fd = new FormData(document.form1);

            fetch('data-insert-api.php', {
                    method: 'POST',
                    body: fd
                })
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
                            location.href = 'member_list.php';
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