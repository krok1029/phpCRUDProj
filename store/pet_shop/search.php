<?php
$db_host = "192.168.27.115";
//$db_host = "localhost";
$db_name = "pet_adoption_proj";
$db_user = "root";
$db_pass = "";

$dsn = "mysql:host={$db_host};dbname={$db_name}";


$pdo_options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"
];

$pdo = new PDO($dsn, $db_user, $db_pass, $pdo_options);
// 以上連線

$output = '';

if(isset($_POST['search'])){
    $searchq = $_POST['search'];
    $searchq = preg_replace("#[.]#i","",$searchq);

    $query = $pdo->query("SELECT * FROM `shop_goods` WHERE name LIKE '%$searchq%'");
    $count = $query->rowCount();
    // echo $count;
    if($count == 0){
         $output = '沒有此商品喔!';
    }else{
        while($row = $query->fetch()){
            $goods_name = $row['name'];
            // $category_sid_name = $row['category_sid'];
            $goods_id = $row['goods_id'];

            $output .= '<div>' .$goods_name.'</div>';
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>search</title>
</head>
<body>

<div>
            <form action="search.php" method="post" class="form-inline my-2 my-lg-0">
                <input class="form-control mr-sm-2" type="text" name="search" placeholder="商品名稱搜尋" aria-label="Search">
                <button class="btn btn-outline-primary my-2 my-sm-0" type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>

<?php print($output);?>
    
</body>
</html>