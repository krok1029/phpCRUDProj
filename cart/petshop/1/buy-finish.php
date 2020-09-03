<?php
require __DIR__ . '/__connect_db.php';
$pageName = 'buy-start';
if (empty($_SESSION['cart']) or empty($_SESSION['member'])) {
    header('Location: product-list.php');
    exit;
}

// *** 抓到當下的價格資訊 *** begin
$sids = array_column($_SESSION['cart'], 'sid');
$sql = "SELECT * FROM `products` WHERE `sid` IN (" . implode(',', $sids) . ")";
$productData = [];
$stmt = $pdo->query($sql);
while ($r = $stmt->fetch()) {
    $productData[$r['sid']] = $r;
}
$totalPrice = 0;
foreach ($_SESSION['cart'] as $k => $v) {
    $_SESSION['cart'][$k]['price'] = $productData[$v['sid']]['price'];

    $totalPrice += $_SESSION['cart'][$k]['price'] * $v['quantity'];
}
// *** 抓到當下的價格資訊 *** end

// 寫入 orders
$sql = "INSERT INTO `orders`(`member_sid`, `amount`, `order_date`) VALUES (?, ? , NOW())";
$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['member']['id'], $totalPrice]);

$order_sid = $pdo->lastInsertId();  // 訂單流水號

// 寫入 order_details
$sql2 = "INSERT INTO `order_details`(`order_sid`, `product_sid`, `price`, `quantity`) VALUES (?,?,?,?)";
$stmt2 = $pdo->prepare($sql2);

foreach ($_SESSION['cart'] as $i) {
    $stmt2->execute([$order_sid, $i['sid'], $i['price'], $i['quantity']]);
}

// 清除購物車內容
unset($_SESSION['cart']);


?>
<?php include __DIR__ . '/__html_head.php' ?>
<?php include __DIR__ . '/__navbar.php' ?>
<div class="container">

    <div class="row">
        <div class="col">
            <h2>感謝訂購</h2>
        </div>
    </div>


</div>
<?php include __DIR__ . '/__scripts.php' ?>
<script>

</script>
<?php require __DIR__ . '/__html_foot.php' ?>