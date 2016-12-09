<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>プロフィール変更</title>
    <style>
        #withdrawal {
            background-color: #1e90ff;
        }
    </style>
</head>
<body>
    <?php if (!empty($err_msg)) {
            foreach ($err_msg as $print)
            print('<span>' .$print .'</span><br />'); // エラーメッセージ
    } ?>
    <?php if (is_null($data[0]['withdrawal_flag'])) { ?> 
    <p>退会したアカウントは元にはもどりません</p>
    <p>本当に退会しますか？</p>
    <form action="twitter_withdrawal.php" method="post" >
        <input id="withdrawal" type="submit" value="退会する">
        <input type="hidden" name="function" value="delete">
    </form>
    <form action="twitter_prof.php" method="post" >
        <input type="submit" value="戻る">
    </form>
    <?php } else { ?>
    <p>退会が完了しました</p>
    <p>今までのご利用ありがとうございました</p>
    <form action="twitter_logout.php" method="post">
        <input type="submit" value="トップページへ">
    </form>
    <?php } ?>
</body>
</html>