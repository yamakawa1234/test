<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ユーザーのページ</title>
    <style>
    body {
        width: 1000px;
    }
    #tweet {
        padding: 0px;
        width: 300px;
        height: 100px;
    }
    #prof_box {
        float: left;
        width: 300px;
    }
    #tweet_box {
        float: left;
        width: 600px;
    }
    textarea {  
    resize: none;  
    }
    .mypic {
        width: 65px;
        height: 65px;
        border: medium solid #000000;
    }
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
    <!--
    <div id=serch_box>
    <form action="twitter_serch.php" method="post">
        <label for="mail">アカウント名またはメールアドレスから友達を検索</label>
        <input type="text" id="serch" name="serch" value="">
        <input type="submit" value="検索">
        <input type="hidden" name="function" value="serch">
        <input type="hidden" name="back_page" value="tweet">
        <input type="hidden" name="user_name" value="<?php print $data[0]['user_name']; ?>">
    </form>
    </div>
    -->
    <div id=prof_box>
        <form action="twitter_home.php" method="post" >
        <input type="submit" value="マイページへ">
    </form>
    <form action="twitter_logout.php" method="post">
        <input type="submit" value="ログアウト">
    </form>
    <!--
    <p><img class="mypic" src="./pic_twitter_prof/<?php if ($data[0]['extension'] === 'DUMMY') {
        print $data[0]['extension'] .'.jpg';    
    } else {
        print $data[0]['user_id'] .'.' .$data[0]['extension'];
    } ?>"></p>
    <p><?php print $data[0]['user_nickname']; ?></p>
    <p><?php print $data[0]['user_name']; ?></p>
    <p><?php print $data[0]['place']; ?></p>
    <p><?php print $data[0]['prof']; ?></p>
    <p><?php print $data[0]['date']; ?>に登録</p>
    <?php if ($user_id != $data[0]['user_id']) {
        if ($follow_status == true) {?>
    <form action="twitter_tweet.php" method="get">
        <input type="submit" value="フォローをやめる">
        <input type="hidden" name="function" value="unfollow">
        <input type="hidden" name="user_name" value="<?php print $data[0]['user_name']; ?>">
    </form>
        <?php } else { ?>
    <form action="twitter_tweet.php" method="get">
        <input type="submit" value="フォローする">
        <input type="hidden" name="function" value="follow">
        <input type="hidden" name="user_name" value="<?php print $data[0]['user_name']; ?>">
    </form>
        <?php } ?>
    <form action="twitter_DM.php" method="get">
        <input type="submit" value="ダイレクトメッセージを送る">
        <input type="hidden" name="receive_id" value="<?php print $data[0]['user_id']; ?>">
        <input type="hidden" name="back_page" value="tweet">
        <input type="hidden" name="user_name" value="<?php print $data[0]['user_name']; ?>">
    </form>
    <?php } ?>
    <form action="twitter_friend_list.php" method="post">
        <input type="submit" value="フォロー　　<?php print $follow ?>">
        <input type="hidden" name="display_function" value="follow">
        <input type="hidden" name="user_id" value="<?php print $data[0]['user_id']; ?>">
        <input type="hidden" name="control_id" value="<?php print $data[0]['user_id']; ?>">
        <input type="hidden" name="back_page" value="tweet">
        <input type="hidden" name="user_name" value="<?php print $data[0]['user_name']; ?>">
    </form>
    <form action="twitter_friend_list.php" method="post">
        <input type="submit" value="フォロワー　<?php print $follower ?>">
        <input type="hidden" name="display_function" value="follower">
        <input type="hidden" name="user_id" value="<?php print $data[0]['user_id']; ?>">
        <input type="hidden" name="control_id" value="<?php print $data[0]['user_id']; ?>">
        <input type="hidden" name="back_page" value="tweet">
        <input type="hidden" name="user_name" value="<?php print $data[0]['user_name']; ?>">
    </form>
    <form action="twitter_tweet.php" method="get">
        <input type="submit" value="ツイート　　<?php print $tweet_count; ?>">
        <input type="hidden" name="function" value="tweet">
        <input type="hidden" name="user_name" value="<?php print $data[0]['user_name']; ?>">
    </form>
    <?php if ($user_id != $data[0]['user_id']) {
        if ($block_me == true) { ?>
    <form action="twitter_tweet.php" method="get">
        <input type="submit" value="このユーザーをブロック">
        <input type="hidden" name="function" value="block">
        <input type="hidden" name="user_name" value="<?php print $data[0]['user_name']; ?>">
    </form>
    <?php } else { ?>
    <form action="twitter_tweet.php" method="get">
        <input type="submit" value="ブロック解除">
        <input type="hidden" name="function" value="unblock">
        <input type="hidden" name="user_name" value="<?php print $data[0]['user_name']; ?>">
    </form>
    <?php }
    } ?>
    </div>
    -->
    <div id=tweet_box>
    <h1><?php print get_get_data('word'); ?></h1>
    <table>
    <?php if (!empty($hash_tag_data)) {
        foreach ($hash_tag_data as $tweet) { ?>
        <tr>
            <td><img class="pic" src="./pic_twitter_prof/<?php if ($tweet['extension'] === 'DUMMY') {
                print $tweet['extension'] .'.jpg';    
            } else {
                print $tweet['user_id'] .'.' .$tweet['extension'];
            } ?>"></td>
            <td><a href="twitter_tweet.php?user_name=<?php print urlencode($tweet['user_name']); ?>"><?php print $tweet['user_name']; ?></a></br>
            <?php print $tweet['user_nickname'] ?></td>
            <td><?php print $tweet['tweet'] ?></td>
            <td><?php print $tweet['tweet_time'] ?></td>
            <!--
            <td>
                <?php if ($tweet['user_id'] === $user_id) { ?>
                <form action="twitter_tweet.php" method="get">
                    <input type="submit" value="削除">
                    <input type="hidden" name="delete_id" value="<?php print $tweet['tweet_id']; ?>">
                    <input type="hidden" name="function" value="delete">
                    <input type="hidden" name="user_name" value="<?php print $data[0]['user_name']; ?>">
                </form>
                <?php } ?>
            </td>
            -->
        </tr>
        <?php }
    } else { ?>
        <div>まだつぶやきはありません</div>
    <?php } ?>
    </table>
    </div>
</body>
</html>