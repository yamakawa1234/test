<?php
if (isset($_GET['my_name']) === TRUE) {
    print 'ここに入力した名前を表示： ' . htmlspecialchars($_GET['my_name'], ENT_QUOTES, 'UTF-8');
} else {
    print '名前が送られていません';
}
if (isset($_GET['my_add']) === TRUE) {
    print 'ここに入力したアドレスを表示： ' . htmlspecialchars($_GET['my_add'], ENT_QUOTES, 'UTF-8');
} else {
    print 'アドレスが送られていません';
}
print $_GET['my_add'];
?>