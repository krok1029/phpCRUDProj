<?php
require __DIR__ . './parts/__connect_db.php';

$sql = "INSERT INTO `forum_article`(`clicks`, `title`, `content`, `picture`, `created_at`) VALUES (0,?,?,?,NOW())";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    $_POST['title'],
    $_POST['content'],
    $_POST['picture'],
]);

echo $stmt->rowCount();
echo 'ok';
