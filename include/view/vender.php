<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>自動販売機</title>
    <style>
        #flex {
            width: 600px;
        }

        #flex .drink {
            //border: solid 1px;
            width: 120px;
            height: 210px;
            text-align: center;
            margin: 10px;
            float: left; 
        }

        #flex span {
            display: block;
            margin: 3px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .img_size {
            height: 100px;
        }
        
        .pict {
            width: 100px;
            height: 120px;
        }

        .red {
            color: #FF0000;
        }

        #submit {
            clear: both;
        }

    </style>
</head>
<body>
    <h1>自動販売機</h1>
    <form action="result_contr.php" method="post">
        <div>金額<input type="text" name="money" value=""></div>
        <div id="flex">
            <?php foreach ($vending_info as $info) { ?>
            <div class="drink">
                <span class="img_size"><img class="pict" src="./pict/<?php print $info['drink_id'] .'.' .$info['picture_name'] ; ?>"></span>
                <span><?php print $info['name']; ?></span>
                <?php if ($info['stock'] <= 0) { ?>
                <span class="red">売り切れ</span>
                <?php } else { ?>
                <span><?php print $info['price']; ?>円</span>
                <input type="radio" name="drink_id" value="<?php print $info['drink_id']; ?>">
                <?php }?>
            </div>
            <?php } ?>
        </div>
        <div id="submit">
            <input type="submit" value="■□■□■ 購入 ■□■□■">
        </div>
    </form>
</body>
</html>