<?php

// MySQL接続情報 
$host   = 'localhost';      // データベースのホスト名又はIPアドレス ※CodeCampでは「localhost」で接続できます
$user   = 'codecamp6475';   // MySQLのユーザ名
$passwd = 'PCTZVCRT';       // MySQLのパスワード
$dbname = 'codecamp6475';   // データベース名

$message         = '';      // 購入処理完了時の表示メッセージ
$money           = 0;       // 入力金額
$err_msg         = array(); // エラーメッセージ
$vending_info    = array(); // 商品テーブル


// コネクション取得
if ($link = mysqli_connect($host, $user, $passwd, $dbname)) {
     
    // 文字コードセット
    mysqli_set_charset($link, 'UTF8');
 
    /**
     * 商品情報を取得
     */
    // SQL
    $sql  = 'SELECT ドリンク情報.ドリンクID, ドリンク情報.ドリンク名, ドリンク情報.値段, ドリンク情報.公開ステータス, ドリンク情報.picture_name,在庫数管理.在庫数 ';
    $sql .= 'FROM ドリンク情報 ';
    $sql .= 'LEFT JOIN 在庫数管理 ON ドリンク情報.ドリンクID = 在庫数管理.ドリンクID  ';
    $sql .= 'ORDER BY ドリンク情報.ドリンクID ASC;';

    // クエリ実行
    if ($result = mysqli_query($link, $sql)) {
        $i = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            
            // 公開ステータスが非公開のものに対しては、公開しない
            if ($row['公開ステータス'] === '1') {
                $vending_info[$i]['drink_id']      = $row['ドリンクID'];
                $vending_info[$i]['name']          = $row['ドリンク名'];
                $vending_info[$i]['price']         = $row['値段'];
                $vending_info[$i]['status']        = $row['公開ステータス'];
                $vending_info[$i]['stock']         = (int)$row['在庫数'];
                //print '在庫数' .$vending_info[$i]['stock'] .'</br>';
                $vending_info[$i]['picture_name']  = $row['picture_name'];
                //htmlspecialcharsのpoint
                //サニタイズ(無毒化)
                //データベースに格納するときに行うのが好ましい
                //updateやinsertを行う際はhtmlspecialcharsを使用すること
                $i++;
            }
        }
    } else {
        $err_msg[] = 'SQL失敗:' . $sql;
    }
}

if (!empty($err_msg)) {
    foreach ($err_msg as $print)
    print($print .'<br />'); // エラーメッセージ
}
?>
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
    <form action="result.php" method="post">
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