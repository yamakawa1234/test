<?php
if (isset($_POST['my_name']) === TRUE) {
    print 'ここに入力した名前を表示： ' . htmlspecialchars($_POST['my_name'], ENT_QUOTES, 'UTF-8');
} else {
    print '名前が送られていません';
}
if (isset($_POST['my_add']) === TRUE) {
    print 'ここに入力したアドレスを表示： ' . htmlspecialchars($_POST['my_add'], ENT_QUOTES, 'UTF-8');
} else {
    print 'アドレスが送られていません';
}
print $_POST['my_add'];
?>