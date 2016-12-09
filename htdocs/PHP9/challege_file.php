<?php
 
//$filename = './file_write.txt';
$filename = '../PHP9/challenge_log.txt';
 
//REQUEST_METHOD:いまのページがPOST or GETによってオープンしたかどうか
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // date関数を使って日付を取得
    date_default_timezone_set('Asia/Tokyo');

    $comment = date('Y年m月d日 H:i:s').'    '.$_POST['comment']. "\n";
    //$comment = $_POST['comment'];

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
    <title>c課題</title>
</head>
<body>
    <h1>課題</h1>
 
    <form method="post">
        <label>発言:<input type="text" name="comment">
        <input type="submit" name="submit" value="送信"></label>
    </form>
 
    <p>発言一覧</p>
<?php foreach ($data as $read) { ?>
    <p><?php print $read; ?></p>
<?php } ?>
</body>
</html>
