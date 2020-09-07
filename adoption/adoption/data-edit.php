<?php
$page_title = '編輯領養資料';
$page_name = 'data-edit';
require __DIR__ . '/parts/__connect_db.php';
require __DIR__ . '/parts/__admin_required.php';
$pet_id = isset($_GET['pet_id']) ? intval($_GET['pet_id']) : 0;
if (empty($pet_id)) {
    header('Location: data-list.php');
    exit;
}

$sql = " SELECT * FROM pet_info_master WHERE pet_id=$pet_id";
$row = $pdo->query($sql)->fetch();
if (empty($row)) {
    header('Location: data-list.php');
    exit;
}



$sql = "SELECT * FROM `tag_list`";
$tag = $pdo->query($sql)->fetchAll();

$sql = "SELECT tag_id FROM `pet_info_detail` where pet_id=$pet_id";
$tagCheck = $pdo->query($sql)->fetchAll();

$tagList = [];
for ($i = 0; $i < count($tagCheck); $i++) {

    $tagList[] = $tagCheck[$i]['tag_id'];
}

// if (in_array("4", $tagList)) {
//     echo "Got 4";
// }
// print_r($tagList);

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
                    <h5 class="card-title">編輯商品</h5>

                    <form name="form1" onsubmit="checkForm(); return false;" novalidate>
                        <input type="hidden" name="pet_id" value="<?= $row['pet_id'] ?>">
                        <div class="form-group">
                            <label for="name"><span class="red-stars">**</span> 名稱</label>
                            <input type="text" class="form-control" id="name" name="name" required value="<?= htmlentities($row['name']) ?>">
                            <small class="form-text error-msg"></small>
                        </div>


                        <!-- <div class="form-group">
                            <label for="dog_cat"><span class="red-stars">**</span> 貓咪/狗勾</label>
                            <input type="type" class="form-control" id="dog_cat" name="dog_cat" value="<?= htmlentities($row['dog_cat']) ?>">
                            <small class="form-text error-msg"></small>
                        </div> -->



                        <div class="form-group">
                            <label for="category_sid"><span class="red-stars">**</span> 貓咪/狗勾</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="Radio1" value="cat" name="dog_cat" <?php echo $row['dog_cat'] == 'cat' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="Radio1">貓咪</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="Radio2" value="dog" name="dog_cat" <?php echo $row['dog_cat'] == 'dog' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="Radio2">狗勾</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="age"><span class="red-stars">**</span> 年齡</label>
                            <input type="tel" class="form-control" id="age" name="age" value="<?= htmlentities($row['age']) ?>">
                            <small class="form-text error-msg"></small>
                        </div>
                        <div class="form-group">
                            <label for="area"><span class="red-stars">**</span> 所在地區</label>
                            <input type="tel" class="form-control" id="area" name="area" value="<?= htmlentities($row['area']) ?>">
                            <small class="form-text error-msg"></small>
                        </div>
                        <div class="form-group">
                            <label for="address"><span class="red-stars">**</span> 地址</label>
                            <input type="tel" class="form-control" id="address" name="address" value="<?= htmlentities($row['address']) ?>">
                            <small class="form-text error-msg"></small>
                        </div>
                        <div class="form-group">
                            <label for="description"><span class="red-stars">**</span> 介紹</label>
                            <input type="tel" class="form-control" id="description" name="description" value="<?= htmlentities($row['description']) ?>">
                            <small class="form-text error-msg"></small>
                        </div>


                        <!-- /////////tag//////////// -->
                        <div class="form-group">
                            <label for="">Tags</label><br>
                            <?php foreach ($tag as $h) : ?>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="tags[]" id="tags<?= $h['tag_id'] ?>" value="<?= $h['tag_id'] ?>" <?= in_array($h['tag_id'], $tagList) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="tags<?= $h['tag_id'] ?>"><?= $h['description'] ?></label>
                                </div>
                            <?php endforeach; ?>
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

            fetch('data-edit-api.php', {
                    method: 'POST',
                    body: fd
                })
                // .then(r => r.text())
                // .then(r => console.log(r))
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
<?php include __DIR__ . '/parts/__html_foot.php'; ?>