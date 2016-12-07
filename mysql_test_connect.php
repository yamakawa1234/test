<html>
<head><title>PHP TEST</title></head>
<body>

<?php

require_once 'MDB2.php';

$dsn = 'mysql://root:root@localhost/mre_sbi_2014';

$db = MDB2::connect($dsn);
if (PEAR::isError($db)) {
    die($db->getMessage());
}

print('接続に成功しました');

$db->disconnect();

?>

</body>
</html>
