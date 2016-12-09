<pre>
<?php
 
$host     = 'localhost'; // データベースのホスト名又はIPアドレス ※CodeCampでは「localhost」で接続できます
$username = 'codecamp6475';  // MySQLのユーザ名
$passwd   = 'PCTZVCRT';    // MySQLのパスワード
$dbname   = 'codecamp6475';    // データベース名
 
$link = mysqli_connect($host, $username, $passwd, $dbname);
 
// 接続成功した場合
if ($link) {
 
    // 文字化け防止
    mysqli_set_charset($link, 'utf8');
 
    //$query = 'INSERT INTO goods_table(goods_name, price) VALUES(\'ボールペン\', 80)';
    //$query = 'UPDATE goods_table SET price = 60 WHERE goods_id = 6';
    //$query = 'DELETE FROM goods_table WHERE goods_id = 6';


 
    // クエリを実行します
     if (mysqli_query($link, $query) === TRUE) {
        print '成功';
    } else {
        print '失敗';
    }
   
    // 接続を閉じます
    mysqli_close($link);
 
// 接続失敗した場合
} else {
    print 'DB接続失敗';
}
 
?>
</pre>