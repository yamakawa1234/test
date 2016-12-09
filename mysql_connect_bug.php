<html>
<head><title>PHP TEST</title></head>
<body>

<?php
ini_set( 'display_errors', 1 );
print('<p>successed</p>');
$link = mysql_connect('localhost', 'root', 'root');
if (!$link) {
    die('failed'.mysql_error());
}

print('<p>successed</p>');

// MySQLに対する処理

$close_flag = mysql_close($link);

if ($close_flag){
    print('<p>切断に成功しました。</p>');
}

?>
</body>
</html>
