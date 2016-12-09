<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>商品追加</title>
</head>
<body>
<?php if (count($err_msg)) { ?>
<?php   foreach ($err_msg as $value) {?>
    <p><?php print $value; ?></p>
<?php   } ?>
<?php } ?>
    <form action="goods_insert.php" method="POST">
        商品名:<input type="text" name="goods_name" value="<?php print $goods_name; ?>">
        価格:<input type="text" name="price" value="<?php print $price; ?>">
        <input type="submit" value="追加">
    </form>
</body>
</html>