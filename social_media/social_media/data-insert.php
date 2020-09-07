<?php
$page_title = '新增資料';
$page_name = 'data-insert';
require __DIR__ . './parts/__connect_db.php';
?>

<?php require __DIR__ . './parts/__html_head.php'; ?>
<style>
    span.red-stars {
        color: red;
    }

    small.error-msg {
        color: red;
    }
</style>
<?php require __DIR__ . './parts/__navbar.php'; ?>
<div class="container">
    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">新增資料</h5>
                    <form name="form1" onsubmit="checkForm(); return false; " novalidate>
                        <div class="form-group">
                            <label for="title"><span class="red-stars">**</span> title</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                            <small class="form-text error-msg"></small>
                        </div>
                        <div class="form-group">
                            <label for="content"><span class="red-stars">**</span> content</label>
                            <textarea class="form-control" id="content" name="content" cols="30" rows="10" required></textarea>
                            <small class="form-text error-msg"></small>

                        </div>
                        <div class="form-group">
                            <label for="picture">picture</label>
                            <input type="text" class="form-control" id="picture" name="picture">
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . './parts/__scripts.php'; ?>
<script>
    const $title = document.querySelector('#title');
    const $content = document.querySelector('#content');
    const r_fields = [$title, $content];

    function checkForm() {
        let isPass = true;

        r_fields.forEach(el => {
            el.style.borderColor = '#CCCCCC';
            el.nextElementSibling.innerHTML = '';
        })
        if (document.form1.title.value.length < 2) {
            isPass = false;
            $title.style.borderColor = 'red';
            $title.nextElementSibling.innerHTML = '標題請勿少於2字';
        }
        if (document.form1.content.value.length < 15) {
            isPass = false;
            $content.style.borderColor = 'red';
            $content.nextElementSibling.innerHTML = '內容至少15字';
        }

        if (isPass) {
            const fd = new FormData(document.form1);
            fetch('data-insert-api.php', {
                    method: 'POST',
                    body: fd
                })
                .then(r => r.text())
                .then(str => {
                    console.log(str);
                });
        }


    }
</script>
<?php require __DIR__ . './parts/__html_foot.php'; ?>