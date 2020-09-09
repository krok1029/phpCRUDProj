<?php
$page_title = '編輯資料';
$page_name = 'data-edit';
require __DIR__ . './parts/__connect_db.php';
require __DIR__ . './parts/__admin_required.php';

$sid = isset($_GET['sid']) ? intval($_GET['sid']) : 0;
if (empty($sid)) {
    header('Location: data-list.php');
    exit;
}

$sql = " SELECT * FROM forum_article WHERE sid=$sid";
$row = $pdo->query($sql)->fetch();
if (empty($row)) {
    header('Location: data-list.php');
    exit;
}

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
                    <h5 class="card-title">編輯資料</h5>
                    <form name="form1" onsubmit="checkForm(); return false; " novalidate>
                        <input type="hidden" name="sid" value="<?= $row['sid'] ?>">
                        <div class="form-group">
                            <label for="title"><span class="red-stars">**</span> title</label>
                            <input type="text" class="form-control" id="title" name="title" required value="<?= htmlentities($row['title']) ?>">
                            <small class="form-text error-msg"></small>
                        </div>
                        <div class="form-group">
                            <label for="ptype_sid">分類</label>
                            <select class="form-control" id="ptype_sid" name="ptype_sid">
                                <?php foreach ($ptype as $p) : ?>
                                    <option value="<?= $p['sid'] ?>" <?= $row['type_sid'] == $p['sid'] ? 'selected' : '' ?>><?= $p['type'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="issue_sid">主題</label>
                            <select class="form-control" id="issue_sid" name="issue_sid">
                                <?php foreach ($issue as $i) : ?>
                                    <option value="<?= $i['sid'] ?>" <?= $row['issue_sid'] == $i['sid'] ? 'selected' : '' ?>><?= $i['name'] ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="content"><span class="red-stars">**</span> content</label>
                            <textarea class="form-control" id="content" name="content" cols="30" rows="10" required><?= htmlentities($row['content']) ?></textarea>
                            <small class="form-text error-msg"></small>

                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-warning" onclick="file_input.click()">更換圖片</button>
                            <input type="hidden" id="picture" name="picture" value="<?= htmlentities($row['picture']) ?>">
                            <img src="./uploads/<?= $row['picture'] ?>" alt="" id="myimg" width="250px">
                            <br> </div>

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
    const $issue_sid = document.querySelector('#issue_sid')
    const $ptype_sid = document.querySelector('#ptype_sid')
    const r_allitems = [$title, $content, picture, $issue_sid, $ptype_sid];

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
<?php require __DIR__ . './parts/__html_foot.php'; ?>