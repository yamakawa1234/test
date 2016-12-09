<?php
// 変数初期化
$name = '';
$gender = '';
$knowMail = '';
$knowMailOk = 'OK';
print_r($_POST);
if (isset($_POST['send']) === TRUE) {
if (isset($_POST['name']) === TRUE) {
    $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
?>
    <p>ここに入力したお名前を表示：<?php print $name; ?></p>
<?php
}
if (isset($_POST['gender']) === TRUE) {
    $gender = htmlspecialchars($_POST['gender'], ENT_QUOTES, 'UTF-8');
?>
    <p>ここに入力した性別を表示：<?php print $gender; ?></p>
<?php
}
if (isset($_POST['knowMail']) === TRUE) {
    $knowMail = htmlspecialchars($_POST['knowMail'], ENT_QUOTES, 'UTF-8');
?>   
<?php
    } else {
            $knowMailOk = 'NG';        
    }
?>
    <p>ここにメールを受け取るか表示：<?php print $knowMailOk; ?></p>
<?php
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>challenge_super_global.php</title>
</head>
<body>
    <h2>課題</h2>
<?php if ($gender === '男' || $gender === '女') { ?>
    <p>あなたの性別は「<?php print $gender; ?>」です</p>
<?php } ?>
    <form method="post">
        <p>お名前： <input type="text" name="name"></p>
        <p>性別：<input type="radio" name="gender" value="男" <?php if ($gender === '男') { print 'checked'; } ?>>男
        <input type="radio" name="gender" value="女" <?php if ($gender === '女') { print 'checked'; } ?>>女</p>
        <p><input type="checkbox" name="knowMail" value="1" <?php if ($knowMail === '1') { print 'checked'; } ?>> お知らせメールを受け取る</p>
        <p><input type="submit" name="send" value="送信"></p>
    </form>
</body>
</html>