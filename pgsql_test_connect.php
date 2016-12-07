<html>
<head><title>PHP TEST</title></head>
<body>

<?php

// エラー出力する場合
ini_set( 'display_errors', 1 );

require_once 'MDB2.php';

//$dsn = 'mysql://root:root@localhost/mre_sbi_2014';
$dsn = 'pgsql://postgres@localhost/mre_softbank';
//$dsn = 'pgsql://www:www@localhost/template0';

$db = MDB2::connect($dsn);
if (PEAR::isError($db)) {
  print('大失敗');
    die($db->getMessage());
}

print('接続に成功しました');

$db->disconnect();

?>

</body>
</html>
