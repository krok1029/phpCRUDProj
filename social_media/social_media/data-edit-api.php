<?php
require __DIR__ . './parts/__connect_db.php';

$sql = "UPDATE `forum_article` SET 
`title`=?,
`content`=?,
`picture`=?,
`Last_updated`=NOW()
WHERE `sid`=?";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    $_POST['title'],
    $_POST['content'],
    $_POST['picture'],
    $_POST['sid'],
]);

echo $stmt->rowCount();
echo 'ok';
