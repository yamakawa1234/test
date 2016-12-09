<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>フォローorフォロワー</title>
    <style>
    .pic {
        width: 45px;
        height: 45px;
    }
    </style>
</head>
<body>
    <?php if (!empty($err_msg)) {
            foreach ($err_msg as $print)
            print('<span>' .$print .'</span><br />'); // エラーメッセージ
    } ?>
    <div>
        <table>
            <?php if (!empty($data)) {
                foreach ($data as $info) { ?>
            <tr>
                <td><img class="pic" src="./pic_twitter_prof/<?php if ($info['extension'] === 'DUMMY') { print $info['extension'] .'.jpg'; } else { print $info['target_id'] .'.' .$info['extension']; } ?>"></td>
                <td><a href="twitter_tweet.php?user_name=<?php print urlencode($info['user_name']); ?>"><?php print $info['user_nickname']; ?></td>
                <td><?php print $info['place']; ?></td>
                <td><?php print $info['prof']; ?></td>
                <?php if (($control_id === $user_id) or (($info['user_id'] != $user_id) and ($info['followed_id'] != $user_id))) { ?>
                <td><form action="twitter_friend_list.php" method="post">
                    <input type="hidden" name="back_page" value="<?php print $back_page; ?>">
                    <input type="submit" value="<?php print $info['display']; ?>"></td>
                    <input type="hidden" name="display_function" value="<?php print $info['display_function']; ?>">
                    <input type="hidden" name="function" value="<?php print $info['function']; ?>">
                    <input type="hidden" name="user_id" value="<?php print $info['user_id']; ?>">
                    <input type="hidden" name="followed_id" value="<?php print $info['followed_id']; ?>">
                    <input type="hidden" name="control_id" value="<?php print $control_id; ?>">
                    <input type="hidden" name="user_name" value="<?php print $user_name; ?>">
                </form></td>
                <?php } ?>
                <!--
                <td>拡張子</br><?php print $info['extension']; ?></td>
                <td>target_id</br><?php print $info['target_id']; ?></td>
                <td>display_function</br><?php print $info['display_function']; ?></td>
                <td>function</br><?php print $info['function']; ?></td>
                <td>user_id</br><?php print $info['user_id']; ?></td>
                <td>followed_id</br><?php print $info['followed_id']; ?></td>
                <td>control_id</br><?php print $control_id; ?></td>
                -->
            </tr>
            <?php 
                }
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
    </form>
    <?php } ?>
</body>
</html>