<?php
$page_title = '社群討論區';
$page_name = 'data-list';
require __DIR__ . './parts/__connect_db.php';

$perPage = 5; // 每頁有幾筆資料

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

$t_sql = "SELECT COUNT(1) FROM `forum_article`";
$totalRows = $pdo->query($t_sql)->fetch(PDO::FETCH_NUM)[0];

// die('~~~'); //exit; // 結束程式
$totalPages = ceil($totalRows / $perPage);

$ptype_sql = "SELECT * FROM pet_type WHERE 1";
$ptype = $pdo->query($ptype_sql)->fetchAll();

$issue_sql = "SELECT * FROM forum_issue WHERE 1";
$issue = $pdo->query($issue_sql)->fetchAll();

$member_sql = "SELECT * FROM `member_list` WHERE 1";
$member1 = $pdo->query($member_sql)->fetchAll();

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

    $sql = sprintf("SELECT * FROM `forum_article` ORDER BY sid DESC LIMIT %s, %s", ($page - 1) * $perPage, $perPage);
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll();
}
?>

<?php require __DIR__ . './parts/__html_head.php'; ?>
<style>
    th {
        white-space: nowrap;
    }
    .member_text{
        color:darkcyan;
    }
</style>
<?php require __DIR__ . './parts/__navbar.php'; ?>
<div class="container">
    <div class="row">
        <div class="col d-flex justify-content-center">
            <nav aria-label="Page navigation example">
                <ul class="pagination">
                    <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $page - 1 ?>">
                            <i class="fas fa-arrow-alt-circle-left"></i>
                        </a>
                    </li>
                    <?php for ($i = $page - 3; $i <= $page + 3; $i++) :
                        if ($i < 1) continue;
                        if ($i > $totalPages) break;
                    ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $page == $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $page + 1 ?>">
                            <i class="fas fa-arrow-alt-circle-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>

        </div>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <?php if (isset($_SESSION['admin'])) : ?>
                    <th scope="col"><i class="fas fa-trash-alt"></i></th>
                    <th scope="col"><i class="fas fa-edit"></i></th>
                <?php endif; ?>
                <th scope="col">#</th>
                <th scope="col">作者</th>
                <th scope="col">圖片</th>
                <th scope="col">標題</th>
                <th scope="col">分類</th>
                <th scope="col">主題</th>
                <th scope="col">內容</th>
                <th scope="col">建立時間</th>
                <th scope="col">最後更新</th>
                <th scope="col">點擊數</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $r) : ?>
                <tr>
                    <?php if (isset($_SESSION['admin'])) : ?>
                        <td>
                            <a href="data-delete.php?sid=<?= $r['sid'] ?>" onclick="ifDel(event)" data-sid="<?= $r['sid'] ?>">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                        <td>
                            <a href="data-edit.php?sid=<?= $r['sid'] ?>">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td>
                    <?php endif; ?>
                    <td><?= $r['sid'] ?></td>
                    <td class="member_text" style="overflow:hidden;white-space:nowrap">
                        <?php foreach ($member1 as $m) : ?>
                            <?= ($m['sid'] == $r['member_sid']) ? $m['name'] : '' ?>
                        <?php endforeach; ?>
                    </td>
                    <td>
                        <img src="./uploads/<?= $r['picture'] ?>" onerror="javascript:this.src='./uploads/pets.png'" alt="" width="150px">
                    </td>
                    <td style="overflow:hidden;white-space:nowrap"><?= strip_tags($r['title']) ?></td>
                    <td style="overflow:hidden;white-space:nowrap">
                        <!-- <?= $r['type_sid'] ?> -->
                        <?php foreach ($ptype as $p) : ?>
                            <?= ($p['sid'] == $r['type_sid']) ? $p['type'] : '' ?>
                        <?php endforeach; ?>
                    </td>
                    <td style="overflow:hidden;white-space:nowrap">
                        <!-- <?= $r['issue_sid'] ?> -->
                        <?php foreach ($issue as $i) : ?>
                            <?= ($i['sid'] == $r['issue_sid']) ? $i['name'] : '' ?>
                        <?php endforeach; ?>
                    </td>
                    <td><?= mb_strimwidth(($r['content']), 0, 30, "...", "utf-8") ?></td>
                    <td><?= $r['created_at'] ?></td>
                    <td><?= $r['Last_updated'] ?></td>
                    <td><?= $r['clicks'] ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>


</div>

<?php require __DIR__ . './parts/__scripts.php'; ?>
<script>
    function ifDel(event) {
        const a = event.currentTarget;
        console.log(event.target, event.currentTarget);
        const sid = a.getAttribute('data-sid');
        if (!confirm(`是否要刪除編號為${sid}的資料?`)) {
            event.preventDefault();
        }

    }
</script>
<?php require __DIR__ . './parts/__html_foot.php'; ?>