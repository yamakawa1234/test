<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>新規会員登録</title>
    <style>
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
    <form action="twitter_registration.php" method="post">
        <label for="account">アカウント名</label><br>
        <label for="account">15文字以内の半角英小文字大文字または数字の組み合わせのみ使用できます</label>
        <input type="text" id="account" name="account" value="<?php print $account; ?>">
        <label for="mail">メールアドレス</label>
        <input type="text" id="mail" name="mail" value="<?php print $mail; ?>">
        <label for="nickName">ニックネーム（こちらがtwitter for engineerで表示される名前になります）</label>
        <input type="text" id="nickName" name="nickName" value="<?php print $nickName; ?>">
        <label for="passwd">パスワード</label><br>
        <label for="account">半角英小文字大文字数字をそれぞれ1種類以上含む8文字以上16文字以下で入力してください</label>
        <input type="password" id="passwd" name="passwd" value="<?php print $password; ?>">
        <input type="submit" value="上記内容で登録">
    </form>
    <form action="twitter_top.php" method="post" >
        <input type="submit" value="キャンセル">
    </form>
</body>
</html>