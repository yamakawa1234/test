<?php
 
$regexp_mail       = '/^[-._a-zA-Z0-9\/]+@[-._a-z0-9]+\.[a-z]{2,4}$/'; // メールアドレスの正規表現
$regexp_password   = '/^[a-z0-9\w,.:;&=+*%$#!?@()~\'\/-]{6,18}/'; // パスワードの正規表現
$error_level = 0; 
$msg = array();
$macthes = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (preg_match($regexp_mail, $_POST['mail'], $macthes) === 1) {
        if ($_POST['mail'] === $macthes[0]) {
            //print '完全一致';
        } else {
            //print '部分一致';
            $msg[] = 'メールアドレスの形式が正しくありません';
        }
    } else {
        //print '不完全一致';
        $msg[] = 'メールアドレスの形式が正しくありません';
    }
    if (preg_match($regexp_password, $_POST['passwd'], $macthes) === 1) {
        if ($_POST['passwd'] === $macthes[0]) {
            //print '完全一致';
        } else {
            //print '部分一致';
            $msg[] = 'パスワードは半角英数記号6文字以上18文字以下で入力してください';
        }
    } else {
        //print '不完全一致';
        $msg[] = 'パスワードは半角英数記号6文字以上18文字以下で入力してください';
    }
    if (empty($msg)) {
    print '登録完了';
    exit;
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>課題</title>
    <style>
        .block {
            display: block;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <form method="post">
        <!--
        <label for=*>はinputのidと関連付けられる
        labelの文字をクリックすると、関連付けられたinputにカーソルが遷移する。
        -->
        <label for="mail">メールアドレス</label>
        <input type="text" class="block" id="mail" name="mail" value="">
        <label for="passwd">パスワード</label>
        <input type="password" class="block" id="passwd" name="passwd" value="">
        <?php foreach ($msg as $value) { ?>
                <p><?php print htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php } ?>
        <button type="submit">登録</button>
    </form>

</body>
</html>