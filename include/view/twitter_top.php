<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ログイン</title>
    <style>
        input {
            display: block;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <h1>twitter for engineer</h1>
    <form action="twitter_login.php" method="post">
        <label for="account">アカウント名またはメールアドレス</label>
        <input type="text" id="account" name="account" value="<?php print $account; ?>">
        <label for="passwd">パスワード</label>
        <input type="password" id="passwd" name="passwd" value="">
        <input type="submit" value="ログイン">
    </form>
<?php if ($login_err_flag === TRUE) { ?>
    <p>アカウント名、メールアドレス又はパスワードが違います</p>
<?php } ?>
    <form action="twitter_new_account.php" method="post">
        <input type="submit" value="新規会員登録">
    </form>
</body>
</html>