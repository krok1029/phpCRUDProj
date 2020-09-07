<?php
$page_title = '修改資料';
$page_name = 'data-edit';
require __DIR__ . './parts/__connect_db.php';

$sid = isset($_GET['sid']) ? intval($_GET['sid']) : 0;
if (empty($sid)) {
    header('Location: data-list.php');
    exit;
}

$sql = "SELECT * FROM `forum_article` WHERE sid=$sid";
$row = $pdo->query($sql)->fetch();
if (empty($row)) {
    header('Location: data-list.php');
    exit;
}

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
                    <h5 class="card-title">編輯資料</h5>
                    <form name="form1" onsubmit="return checkForm()">
                        <div class="form-group">
                            <label for="title"><span class="red-stars">**</span> title</label>
                            <input type="text" class="form-control" id="title" name="title" value="<?= htmlentities($row['title']) ?>">
                            <small class="form-text error-msg"></small>
                        </div>
                        <div class="form-group">
                            <label for="content"><span class="red-stars">**</span> content</label>
                            <textarea class="form-control" id="content" name="content" cols="30" rows="10"><?= htmlentities($row['content']) ?></textarea>
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
    function checkForm() {
        const fd = new FormData(document.form1);
        fetch('data-edit-api.php', {
                method: 'POST',
                body: fd
            })
            .then(r => r.text())
            .then(str => {
                console.log(str);
            })
        return false;
    }
</script>
<?php require __DIR__ . './parts/__html_foot.php'; ?>