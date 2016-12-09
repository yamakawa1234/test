<?php
 
//$filename = './file_write.txt';
$filename = '../PHP8/file_write.php8.txt';
 
//REQUEST_METHOD:いまのページがPOST or GETによってオープンしたかどうか
if ($_SERVER['REQUEST_METHOD'] === 'POST'){
 
    $comment = $_POST['comment'];
 
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
    <title>ファイル操作</title>
</head>
<body>
    <h1>ファイル操作</h1>
 
    <form method="post">
        <input type="text" name="comment">
        <input type="submit" name="submit" value="送信">
    </form>
 
    <p>以下に<?php print $filename; ?>の中身を表示</p>
<?php foreach ($data as $read) { ?>
    <p><?php print $read; ?></p>
<?php } ?>
</body>
</html>
