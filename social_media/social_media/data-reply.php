<?php
$page_title = '留言管理';
$page_name = 'data-reply';
require __DIR__ . './parts/__connect_db.php';

$perPage = 5; // 每頁有幾筆資料

$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

$t_sq2 = "SELECT COUNT(1) FROM `forum_reply`";
$totalRows = $pdo->query($t_sq2)->fetch(PDO::FETCH_NUM)[0];

// die('~~~'); //exit; // 結束程式
$totalPages = ceil($totalRows / $perPage);

$member_sql = "SELECT * FROM `member_list` WHERE 1";
$member1 = $pdo->query($member_sql)->fetchAll();

$article_sql = "SELECT * FROM `forum_article` WHERE 1";
$article1 = $pdo->query($article_sql)->fetchAll();

$rows = [];
if ($totalRows > 0) {
    if ($page < 1) {
        header('Location: data-reply.php');
        exit;
    }
    if ($page > $totalPages) {
        header('Location: data-reply.php?page=' . $totalPages);
        exit;
    };

    $sq2 = sprintf("SELECT * FROM `forum_reply` ORDER BY sid DESC LIMIT %s, %s", ($page - 1) * $perPage, $perPage);
    $stmt = $pdo->query($sq2);
    $rows = $stmt->fetchAll();
}
?>

<?php require __DIR__ . './parts/__html_head.php'; ?>

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
                    <!-- <th scope="col"><i class="fas fa-edit"></i></th> -->
                <?php endif; ?>
                <th scope="col">#</th>
                <th scope="col">標題</th>
                <th scope="col">作者名稱</th>
                <th scope="col">留言者名稱</th>
                <th scope="col">留言內容</th>
                <th scope="col">建立時間</th>
                <?php if (isset($_SESSION['admin'])) : ?>
                    <th scope="col"><i class="fas fa-reply-all"></i></th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($rows as $r) : ?>
                <tr>
                    <?php if (isset($_SESSION['admin'])) : ?>
                        <td>
                            <a href="reply-delete.php?sid=<?= $r['sid'] ?>" onclick="ifDel(event)" data-sid="<?= $r['sid'] ?>">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                        </td>
                        <!-- <td>
                            <a href="data-edit.php?sid=<?= $r['sid'] ?>">
                                <i class="fas fa-edit"></i>
                            </a>
                        </td> -->
                    <?php endif; ?>
                    <td><?= $r['sid'] ?></td>
                    <td style="overflow:hidden;white-space:nowrap">
                        <?php foreach ($article1 as $a1) : ?>
                            <?= ($a1['sid'] == $r['article_sid']) ? strip_tags($a1['title']) : '' ?>
                        <?php endforeach; ?>
                    </td>
                    <td style="overflow:hidden;white-space:nowrap" id="forAuthor">
                        <?php foreach ($article1 as $a1) : ?>
                            <?= ($a1['sid'] == $r['article_sid']) ? strip_tags($a1['member_sid']) : '' ?>
                        <?php endforeach; ?>
                    </td>
                    <td style="overflow:hidden;white-space:nowrap">
                        <div class="member_text">
                            <?php foreach ($member1 as $m) : ?>
                                <?= ($m['sid'] == $r['member_sid']) ? $m['name'] : '' ?>
                            <?php endforeach; ?>
                        </div>
                    </td>
                    <td><?= mb_strimwidth(($r['content']), 0, 30, "...", "utf-8") ?></td>
                    <td><?= $r['created_at'] ?></td>
                    <td>
                        <a href="reply-urer.php?sid=<?= $r['sid'] ?>">
                            <i class="fas fa-reply-all"></i>
                        </a>
                    </td>
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