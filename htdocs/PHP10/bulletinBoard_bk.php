<?php
//2016/01/17
//課題:半角全角spaceのみの入力の時、処理の内容の最適化

//初期化
$err_msg = ''; //エラーメッセージ
 
//$filename = './file_write.txt';
$filename = '../PHP10/log.txt';
 
//REQUEST_METHOD:いまのページがPOST or GETによってオープンしたかどうか
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
    
        $comment = $_POST['name'].' ' .date('Y年m月d日 H:i:s').' '.$_POST['comment']. "\n";
        //$comment = $_POST['comment'];
    
        if (($fp = fopen($filename, 'a')) !== FALSE) {
            if (fwrite($fp, $comment) === FALSE) {
                print 'ファイル書き込み失敗:  ' . $filename;
            }
            fclose($fp);
        }
    }
}

$data = array();
//is_readable:読み込み可能か否か
if (is_readable($filename) === TRUE) {
    //$filenameが読み込み可能の場合、ファイルオープン
    //上書き可能設定
    if (($fp = fopen($filename, 'r')) !== FALSE) {
        while (($tmp = fgets($fp)) !== FALSE) {
            $data[] = htmlspecialchars($tmp, ENT_QUOTES, 'UTF-8');
        }
        fclose($fp);
    }
} else {
    $data[] = 'ファイルがありません';
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
<?php foreach ($data as $read) { ?>
    <p><?php print $read; ?></p>
<?php } ?>
</body>
</html>
