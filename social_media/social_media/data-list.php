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
    td {
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
<?php require __DIR__ . './parts/__navbar.php'; ?>
<div class="container">
    <div class="row">
        <div class="col d-flex justify-content-end">
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
    <div class="row">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col"><i class="fas fa-trash-alt"></i></th>
                    <th scope="col"><i class="fas fa-edit"></i></th>
                    <th scope="col">#</th>
                    <th scope="col">圖片</th>
                    <th scope="col">建立時間</th>
                    <th scope="col">最後更新</th>
                    <th scope="col">標題</th>
                    <th scope="col">分類</th>
                    <th scope="col">主題</th>
                    <th scope="col">內容</th>
                    <th scope="col">點擊數</th>
                </tr>
            </thead>
            <tbody>
                <div class="row">

                </div>
                <?php foreach ($rows as $r) : ?>
                    <tr>
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
                        <td><?= $r['sid'] ?></td>
                        <td><?= $r['picture'] ?></td>
                        <td><?= $r['created_at'] ?></td>
                        <td><?= $r['Last_updated'] ?></td>
                        <td style="overflow:hidden;white-space:nowrap"><?= strip_tags($r['title']) ?></td>
                        <td style=""><?= strip_tags($r['content']) ?></td>
                        <td><?= $r['clicks'] ?></td>
                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>
    </div>

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