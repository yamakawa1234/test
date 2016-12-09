<?php
//2016/01/24

//初期化
$err_msg = ''; //エラーメッセージ
$bbs_data = array();

$host     = 'localhost'; // データベースのホスト名又はIPアドレス ※CodeCampでは「localhost」で接続できます
$username = 'codecamp6475';  // MySQLのユーザ名
$passwd   = 'PCTZVCRT';    // MySQLのパスワード
$dbname   = 'codecamp6475';    // データベース名
 
$link = mysqli_connect($host, $username, $passwd, $dbname);
 
// 接続成功した場合
if ($link) {
    //REQUEST_METHOD:いまのページがPOSTによってオープンしたかどうか
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        //文字数チェック！！！
        $lenName = mb_strlen($_POST['name']);
        $lenCom = mb_strlen($_POST['comment']);
        //全角スペースを半角スペースに変換
        $chName = mb_convert_kana($_POST['name'], 's', 'UTF-8');
        $chCom = mb_convert_kana($_POST['comment'], 's', 'UTF-8');
        if ($lenName === 0) {
            $err_msg .= 'お名前は必須です。\n';
        }
        if ($lenCom === 0) {
            $err_msg .= '発言は必須です。\n';
        }
        if ($lenName > 20) {
            $err_msg .= 'お名前は20文字以下です。\n';
        }
        if ($lenCom > 100) {
            $err_msg .= '発言は100文字以下です。\n';
        }
        if (ctype_space($chName)) {
            $err_msg .= 'お名前がスペースです。\n';
        }
        if (ctype_space($chCom)) {
            $err_msg .= '発言がスペースです。\n';
        }
        if ($err_msg === '') {
            // date関数を使って日付を取得
            date_default_timezone_set('Asia/Tokyo');
        
            //削除予定
            //$comment = $_POST['name'].' ' .date('Y年m月d日 H:i:s').' '.$_POST['comment']. "\n";
            //test mode
            //$comment = $_POST['comment'];
            
            // 文字化け防止
            mysqli_set_charset($link, 'utf8');
            
            $query = 'INSERT INTO bbs_table(name, data, comment) VALUES (\'' .$_POST['name'] .'\',\'' .date('Y年m月d日 H:i:s') .'\',\'' .$_POST['comment'] .'\');';
            //test mode
            //print $query;
            
            // クエリを実行します
            $result = mysqli_query($link, $query);
            }
    }
    
            // 文字化け防止
            mysqli_set_charset($link, 'utf8');
            
            //$query = 'SELECT index, name, data, comment FROM bbs_table ORDER BY index ASC;';
            $query = 'SELECT * FROM bbs_table;';
            //$query = 'SELECT goods_id, goods_name, price FROM goods_table ORDER BY goods_id ASC;';
         
            // クエリを実行します
            $result = mysqli_query($link, $query);
            
            // 1行ずつ結果を配列で取得します
            while ($row = mysqli_fetch_array($result)) {
                $bbs_data[] = $row;
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
    <title>ひとこと掲示板</title>
</head>
<body>
    <h1>みんなの意見交換所</h1>
 
    <form method="post">
        <label>お名前:<input type="text" name="name"><br>
        発言　:<input type="text" name="comment"><br>
        <input type="submit" name="submit" value="発言する"></label>
    </form>
 
    <p>発言一覧</p>
    <table>
    <?php
if (empty ($bbs_data) === FALSE) {
foreach ($bbs_data as $value) {
?>
        <tr>
            <td>投稿者:<?php print htmlspecialchars($value['name'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td>投稿日：<?php print htmlspecialchars($value['data'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td>　<?php print htmlspecialchars($value['comment'], ENT_QUOTES, 'UTF-8'); ?></td>
        </tr>
<?php
}
} else {
?>
        <tr>
            <td>何もヒットしませんでした</td>
        </tr>
<?php
}
?>
    </table>
</body>
</html>
