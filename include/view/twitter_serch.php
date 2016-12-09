<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ユーザー検索結果表示</title>
    <style>
    .pic {
        width: 30px;
        height: 30px;
    }
    </style>
</head>
<body>
    <?php if (!empty($err_msg)) {
            foreach ($err_msg as $print)
            print('<span>' .$print .'</span><br />'); // エラーメッセージ
    } ?>
    <div id=serch_box>
    <form action="twitter_serch.php" method="post">
        <label for="mail">アカウント名またはメールアドレスから友達を検索</label>
        <input type="text" id="serch" name="serch" value="<?php print $serch; ?>">
        <input type="submit" value="検索">
        <input type="hidden" name="function" value="serch">
        <input type="hidden" name="back_page" value="<?php print $back_page; ?>">
        <input type="hidden" name="user_name" value="<?php print $user_name; ?>">
    </form>
    </div>
    <div>
        <table>
            <?php if (!empty($data)) {
                foreach ($data as $info) { ?>
            <tr>
                <td>
                <img class="pic" src="./pic_twitter_prof/<?php if ($info['extension'] === 'DUMMY') {
                    print $info['extension'] .'.jpg';    
                } else {
                    print $info['user_id'] .'.' .$info['extension'];
                } ?>">
                </td>
                <td><a href="twitter_tweet.php?user_name=<?php print urlencode($info['user_name']); ?>"><?php print $info['user_nickname']; ?></td>
                <td><?php print $info['place']; ?></td>
                <td><?php print $info['prof']; ?></td>
                <td><form action="twitter_serch.php" method="post">
                    <input type="submit" value="<?php print $info['display']; ?>"></td>
                    <input type="hidden" name="function" value="<?php print $info['function']; ?>">
                    <input type="hidden" name="followed_id" value="<?php print $info['user_id']; ?>">
                    <input type="hidden" name="serch" value="<?php print $serch; ?>">
                    <input type="hidden" name="back_page" value="<?php print $back_page; ?>">
                    <input type="hidden" name="user_name" value="<?php print $user_name; ?>">
                </form></td>
            </tr>
            <?php }
            } ?>
        </table>
    </div>
    <?php if ($back_page === 'home') { ?>
    <form action="twitter_home.php" method="post" >
        <input type="submit" value="戻る">
    </form>
    <?php } ?>
    <?php if ($back_page === 'tweet') { ?>
        <form action="twitter_tweet.php" method="get">
        <input type="submit" value="戻る">
        <input type="hidden" name="user_name" value="<?php print $user_name; ?>">
    <?php } ?>
</body>
</html>