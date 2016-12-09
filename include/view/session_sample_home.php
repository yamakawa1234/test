<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ホーム</title>
</head>
<body>
    <p>ようこそ<?php print $user_name; ?>さん</p>
    <form action="session_sample_logout.php" method="post">
        <input type="submit" value="ログアウト">
    </form>
</body>
</html>