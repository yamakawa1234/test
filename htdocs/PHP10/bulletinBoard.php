<?php
 
//$filename = './file_write.txt';
$filename = '../PHP10/log.txt';
 
//REQUEST_METHOD:いまのページがPOST or GETによってオープンしたかどうか
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    //文字数チェック！！！
    $lenName = mb_strlen($_POST['name']);
    $lenCom = mb_strlen($_POST['comment']);
    switch (true) {
        case $lenName === 0:
?>
<SCRIPT language="JavaScript">
<!--
　　alert("お名前を入力して下さい。");
//-->
</SCRIPT>
<?php
            break;
        case $lenCom === 0:
?>
<SCRIPT language="JavaScript">
<!--
　　alert("発言を入力して下さい。");
//-->
</SCRIPT>
<?php
            break;
        case $lenName > 20:
?>
<SCRIPT language="JavaScript">
<!--
　　alert("お名前は20文字以上で入力してください。");
//-->
</SCRIPT>
<?php
            break;
        case $lenCom > 100:
?>
<SCRIPT language="JavaScript">
<!--
　　alert("発言は100文字以上で入力してください。");
//-->
</SCRIPT>
<?php
            break;
?>            
<?php
        default:
    // date関数を使って日付を取得
    date_default_timezone_set('Asia/Tokyo');

    $comment = $_POST['name'].' ' .date('Y年m月d日 H:i:s').' '.$_POST['comment']. "\n";
    //$comment = $_POST['comment'];


    }

    if (($fp = fopen($filename, 'a')) !== FALSE) {
        if (fwrite($fp, $comment) === FALSE) {
            print 'ファイル書き込み失敗:  ' . $filename;
        }
        fclose($fp);
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
