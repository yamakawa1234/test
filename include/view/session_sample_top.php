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
    <form action="session_sample_login.php" method="post">
        <label for="email">メールアドレス</label>
        <input type="text" id="email" name="email" value="<?php print $email; ?>">
        <label for="passwd">パスワード</label>
        <input type="password" id="passwd" name="passwd" value="">
        <input type="submit" value="ログイン">
    </form>
<?php if ($login_err_flag === TRUE) { ?>
    <p>メールアドレス又はパスワードが違います</p>
<?php } ?>
</body>
</html>