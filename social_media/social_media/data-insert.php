<?php
$page_title = '新增資料';
$page_name = 'data-insert';
require __DIR__ . './parts/__connect_db.php';

$ptype_sql = "SELECT * FROM pet_type WHERE 1";
$ptype = $pdo->query($ptype_sql)->fetchAll();

$issue_sql = "SELECT * FROM forum_issue WHERE 1";
$issue = $pdo->query($issue_sql)->fetchAll();
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
            <div id="infobar" class="alert alert-success" role="alert" style="display: none">
                A simple success alert—check it out!
            </div>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">新增資料</h5>
                    <form name="form1" onsubmit="checkForm(); return false; " novalidate>
                        <div class="form-group">
                            <label for="title"><span class="red-stars">**</span> 標題</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                            <small class="form-text error-msg"></small>
                        </div>

                        <div class="form-group">
                            <label for="ptype_sid">分類</label>
                            <select class="form-control" id="ptype_sid" name="ptype_sid">
                                <?php foreach ($ptype as $p) : ?>
                                    <option value="<?= $p['sid'] ?>"><?= $p['type'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="issue_sid">主題</label>
                            <select class="form-control" id="issue_sid" name="issue_sid">
                                <?php foreach ($issue as $i) : ?>
                                    <option value="<?= $i['sid'] ?>"><?= $i['name'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="content"><span class="red-stars">**</span> 內容</label>
                            <textarea class="form-control" id="content" name="content" cols="30" rows="10" required></textarea>
                            <small class="form-text error-msg"></small>

                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-warning" onclick="file_input.click()">上傳圖片</button>
                            <input type="hidden" id="picture" name="picture">
                            <img src="" alt="" id="myimg" width="250px">
                            <br>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                    <input type="file" id="file_input" style="display: none">
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
    const infobar = document.querySelector('#infobar');
    const submitBtn = document.querySelector('button[type=submit]');
    const file_input = document.querySelector('#file_input');
    const picture = document.querySelector('#picture');

    file_input.addEventListener('change', function(event) {
        console.log(file_input.files)
        const fd = new FormData();
        fd.append('myfile', file_input.files[0]);

        fetch('upload-single-api.php', {
                method: 'POST',
                body: fd
            })
            .then(r => r.json())
            .then(obj => {
                picture.value = obj.filename;
                document.querySelector('#myimg').src = './uploads/' + obj.filename;
            });
    });

    function checkForm() {
        let isPass = true;

        r_fields.forEach(el => {
            el.style.borderColor = '#CCCCCC';
            el.nextElementSibling.innerHTML = '';
        })
        submitBtn.style.display = 'none';

        if ($title.value.length < 2) {
            isPass = false;
            $title.style.borderColor = 'red';
            $title.nextElementSibling.innerHTML = '標題請勿少於2字';
        }
        if ($content.value.length < 15) {
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
<?php require __DIR__ . './parts/__html_foot.php'; ?>