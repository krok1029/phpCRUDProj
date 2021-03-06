<?php
$page_title = '商品列表';
$page_name = 'data-list';
require __DIR__ . '/parts/__connect_db.php';

$perPage = 5; // 每頁有幾筆資料

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

$t_sql = "SELECT COUNT(1) FROM `pet_info_master_g`";

if (isset($_GET['like'])) {
    $like = $_GET['like'];
    $t_sql = "SELECT count(1) 
    FROM `pet_info_master_g` a left join pet_info_detail_g b on a.pet_id = b.pet_id left join tag_list_g c on b.tag_id = c.tag_id 
    WHERE a.name like '%$like%' or a.area like'%$like%' or a.address like '%$like%' or a.description like '%$like%' or c.description like '%$like%' or  a.dog_cat like '%$like%'
    GROUP by a.`pet_id`, a.`name`, a.`dog_cat`, a.`age`, a.`area`, a.`address`, a.`description`, a.`create_time`";
    $totalRows = $pdo->query($t_sql)->rowCount();
    // echo $totalRows;
} else {

    $totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];
}

// die('~~~'); //exit; // 結束程式
$totalPages = ceil($totalRows / $perPage);

$rows = [];
if ($totalRows > 0) {
    if ($page < 1) {
        header('Location: data-list.php');
        exit;
    }
    if ($page > $totalPages) {
        header('Location: data-list.php?page=' . $totalPages);
        exit;
    };

    $sql = sprintf("SELECT * FROM `pet_info_master_g` ORDER BY pet_id DESC LIMIT %s, %s", ($page - 1) * $perPage, $perPage);
    $like = '';
    if (isset($_GET['like'])) {
        $like = $_GET['like'];
        $tsql = "SELECT a.`pet_id`, a.`name`, a.`dog_cat`, a.`age`, a.`area`, a.`address`, a.`description`, a.`create_time` 
        FROM `pet_info_master_g` a left join pet_info_detail_g b on a.pet_id = b.pet_id left join tag_list_g c on b.tag_id = c.tag_id 
        WHERE a.name like '%$like%' or a.area like'%$like%' or a.address like '%$like%' or a.description like '%$like%' or c.description like '%$like%' or  a.dog_cat like '%$like%'
        GROUP by a.`pet_id`, a.`name`, a.`dog_cat`, a.`age`, a.`area`, a.`address`, a.`description`, a.`create_time`";
        $sql = sprintf("ORDER BY a.pet_id DESC LIMIT %s, %s", ($page - 1) * $perPage, $perPage);
        $sql = $tsql . $sql;
    }
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll();
}
# 正規表示式
// https://developer.mozilla.org/zh-TW/docs/Web/JavaScript/Guide/Regular_Expressions
?>
<?php require __DIR__ . '/parts/__html_head.php'; ?>
<?php include __DIR__ . '/parts/__navbar.php'; ?>
<div class="container">
    <div class="row">
        <div class="col d-flex justify-content-start">
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item ">
                        <input type="type" class="form-control" id="searchInput" name="searchInput" value="">
                    </li>

                    <li class="page-item ">
                        <a class="page-link" href="javascript: like()">
                            <i class="fas fa-search"></i>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>



        <div class="col d-flex justify-content-end">
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $page - 1 ?>">
                            <i class="fas fa-arrow-circle-left"></i>
                        </a>
                    </li>
                    <?php for ($i = $page - 3; $i <= $page + 3; $i++) :
                        if ($i < 1) continue;
                        if ($i > $totalPages) break;
                    ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link" href="?like=<?= $like ?>&page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $page == $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link" href="?like=<?= $like ?>&page=<?= $page + 1 ?>">
                            <i class="fas fa-arrow-circle-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>

        </div>
    </div>


    <table class="table table-striped">
        <!-- `sid`, `name`, `email`, `mobile`, `birthday`, `address`, `created_at` -->
        <thead>
            <tr>
                <?php if (isset($_SESSION['admin1'])) : ?>
                    <th scope="col"><i class="fas fa-trash-alt"></i></th>
                    <!-- <th scope="col"><i class="fas fa-user-times"></i></th> -->
                <?php endif; ?>
                <!-- <th scope="col">#</th> -->
                <th scope="col">序號</th>
                <th scope="col">名稱</th>
                <th scope="col">貓咪/狗勾</th>
                <th scope="col">年齡</th>
                <th scope="col">所在地區</th>
                <th scope="col">地址</th>
                <th scope="col">介紹</th>
                <th scope="col">建立時間</th>
                <?php if (isset($_SESSION['admin1'])) : ?>
                    <th scope="col"><i class="fas fa-edit"></i></th>
                <?php endif; ?>
                <!-- <th scope="col"><i class="fas fa-heart"></i></th> -->


            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $r) : ?>
                <tr>
                    <?php if (isset($_SESSION['admin1'])) : ?>
                        <td><a href="data-delete.php?pet_id=<?= $r['pet_id'] ?>" onclick="ifDel(event)" data-sid="<?= $r['pet_id'] ?>">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                    <?php endif; ?>
                    <td><?= $r['pet_id'] ?></td>
                    <?php
                    $sql = sprintf("SELECT b.tag_id , b.description
                            FROM `pet_info_detail_g` a join tag_list_g b  on a.tag_id = b.tag_id
                            where  pet_id = %s ", $r['pet_id']);
                    $stmtd = $pdo->query($sql);
                    $rowsd = $stmtd->fetchAll();
                    $tagStr = '';
                    for ($i = 0; $i < count($rowsd); $i++) {
                        $tagStr = $tagStr . $rowsd[$i]['description'] . ',';
                    }
                    

                    ?>
                    <td data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="<?= $tagStr ?>" style="cursor:pointer"><?= $r['name'] ?></td>
                    <td><?= $r['dog_cat'] ?></td>
                    <td><?= $r['age'] ?></td>
                    <td><?= $r['area'] ?></td>
                    <td><?= $r['address'] ?></td>
                    <td><?= $r['description'] ?></td>
                    <td><?= $r['create_time'] ?></td>

                    <?php if (isset($_SESSION['admin1'])) : ?>
                        <td><a href="data-edit.php?pet_id=<?= $r['pet_id'] ?>"><i class="fas fa-edit"></i></a></td>
                    <?php endif; ?>
                    <!-- <td><a href="#"><i class="fas fa-heart"></i></i></a></td> -->

                <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php include __DIR__ . '/parts/__scripts.php'; ?>
<script>
    function ifDel(event) {
        const a = event.currentTarget;
        console.log(event.target, event.currentTarget);
        const sid = a.getAttribute('data-sid');
        if (!confirm(`是否要刪除編號為 ${sid} 的資料?`)) {
            event.preventDefault(); // 取消連往 href 的設定
        }
    }

    function delete_it(sid) {
        if (confirm(`是否要刪除編號為 ${sid} 的資料???`)) {
            location.href = 'data-delete.php?sid=' + sid;
        }
    }
</script>
<script>
    $(document).ready(function() {
        $('[data-toggle="popover"]').popover();
    });
</script>
<script>
    const like = () => {
        const search = document.querySelector('#searchInput')
        location.href = 'data-list.php?like=' + search.value

    }
</script>

<?php include __DIR__ . '/parts/__html_foot.php'; ?>