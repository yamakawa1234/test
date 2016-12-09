<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>新規会員登録</title>
    <style>
        .red {
            color: #ff0000;
        }
        .mail {
            display: block;
            margin-bottom: 10px;
        }
        input {
            display: block;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <?php if (!empty($err_msg)) {
        foreach ($err_msg as $print) { ?>
            <span><?php print $print ?></span></br>
        <?php } ?>
    <form action="twitter_new_account.php" method="post" >
        <input type="submit" value="登録画面に戻る">
        <input type="hidden" name="account" value="<?php print $account ?>">
        <input type="hidden" name="mail" value="<?php print $mail ?>">
        <input type="hidden" name="nickName" value="<?php print $nickName ?>">
        <input type="hidden" name="password" value="<?php print $password ?>">
    </form>
    <form action="twitter_top.php" method="post" >
        <input type="submit" value="ログイン画面に戻る">
    </form>
    <?php } else {?>
    <span>登録完了です！</span></br>
    <span>twitter for engineer はあなたを歓迎します！</span></br>
    <form action="twitter_top.php" method="post" >
        <input type="submit" value="ログイン画面に戻る">
    </form>
    <?php } ?>
</body>
</html>