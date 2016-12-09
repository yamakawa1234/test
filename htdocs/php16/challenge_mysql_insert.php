<?php

//初期化
$goods_data = array();
$err_msg = ''; //エラーメッセージ
$head = '追加したい商品の名前と価格を入力してください'; //ヘッダー部

$host     = 'localhost'; // データベースのホスト名又はIPアドレス ※CodeCampでは「localhost」で接続できます
$username = 'codecamp6475';  // MySQLのユーザ名
$passwd   = 'PCTZVCRT';    // MySQLのパスワード
$dbname   = 'codecamp6475';    // データベース名
 
$link = mysqli_connect($host, $username, $passwd, $dbname);
 
// 接続成功した場合
if ($link) {
//POSTによってこのページがオープンされたか？
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //文字数チェック！！！
    $lenName = mb_strlen($_POST['post_Gname']);
    $lenPrice = mb_strlen($_POST['post_Gprice']);
    //全角スペースを半角スペースに変換
    $chName = mb_convert_kana($_POST['post_Gname'], 's', 'UTF-8');
    $chPrice = mb_convert_kana($_POST['post_Gprice'], 's', 'UTF-8');
    if ($lenName === 0) {
        $err_msg .= '商品名を入力してください。\n';
    }
    if ($lenPrice === 0) {
        $err_msg .= '価格を入力してください。\n';
    }
    if ($lenName > 100) {
        $err_msg .= '商品名は100文字以下です。\n';
    }
    if ($lenPrice > 11) {
        $err_msg .= '価格は11文字以下です。\n';
    }
    if (ctype_space($chName)) {
        $err_msg .= '商品名がスペースです。\n';
    }
    if (ctype_space($chPrice)) {
        $err_msg .= '価格がスペースです。\n';
    }
    if ($err_msg === '') {
        // 文字化け防止
        mysqli_set_charset($link, 'utf8');
        
        $query = 'INSERT INTO goods_table(goods_name, price) VALUES (\'' .$_POST['post_Gname'] .'\',' .$_POST['post_Gprice'] .');';
        
        // クエリを実行します
        $result = mysqli_query($link, $query);
        
        if ($result) {
            $head = '追加成功';
        } else {
            $head = '追加失敗';
        }
    } else {
        $head = '追加失敗';
    }
} 

    // 文字化け防止
    mysqli_set_charset($link, 'utf8');
    
    $query = 'SELECT goods_id, goods_name, price FROM goods_table ORDER BY goods_id ASC;';
 
    // クエリを実行します
    $result = mysqli_query($link, $query);
    
    // 1行ずつ結果を配列で取得します
    while ($row = mysqli_fetch_array($result)) {
        $goods_data[] = $row;
    }
    
    // 結果セットを開放します
    //結果オブジェクトが必要なくなった場合は、常に mysqli_free_result() でメモリを開放すべきです。 
    mysqli_free_result($result);
 
    // 接続を閉じます
    mysqli_close($link);
 
// 接続失敗した場合
} else {
    print 'DB接続失敗';
}
 if ($err_msg != '') {
?>
<SCRIPT language="JavaScript">
　　alert('<?php echo $err_msg; ?>');
</SCRIPT>
<?php
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>課題2</title>
    <style type="text/css">
        table, td, th {
            border: solid black 1px;
        }
        table {
            width: 200px;
        }
    </style>
</head>
<body>
    <p><?php print $head ?></p>
    <form method="post">
        <label>商品名:<input type="text" name="post_Gname">
        価格:<input type="text" name="post_Gprice">
        <input type="submit" name="submit" value="追加">
        </label>
    </form>
    <table>
    <caption>商品一覧</caption>
        <tr>
            <th>商品名</th>
            <th>価格</th>
        </tr>
<?php
if (empty ($goods_data) === FALSE) {
foreach ($goods_data as $value) {
?>
        <tr>
            <td><?php print htmlspecialchars($value['goods_name'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php print htmlspecialchars($value['price'], ENT_QUOTES, 'UTF-8'); ?></td>
        </tr>
<?php
}
} else {
?>
        <tr>
            <td>テーブルに何も格納されていません。</td>
        </tr>
<?php
}
?>
    </table>
</body>
</html>