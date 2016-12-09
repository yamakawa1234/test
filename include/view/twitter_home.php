<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ホーム</title>
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
    .recommended_pic {
        width: 15px;
        height: 15px;
    }
    </style>
    <SCRIPT LANGUAGE="JavaScript">
    <!--
        //setTimeout("location.reload()",1000*5);
    //-->
    </SCRIPT>
</head>
<body>
    <?php if (!empty($err_msg)) {
    foreach ($err_msg as $print)
        print('<span>' .$print .'</span><br />'); // エラーメッセージ
    } ?>
    <div id=serch_box>
    <form action="twitter_serch.php" method="post">
        <label for="mail">アカウント名またはメールアドレスから友達を検索</label>
        <input type="text" id="serch" name="serch" value="">
        <input type="submit" value="検索">
        <input type="hidden" name="function" value="serch">
        <input type="hidden" name="back_page" value="home">
        <input type="hidden" name="user_name" value="">
    </form>
    </div>
    <div id=prof_box>
    <form action="twitter_logout.php" method="post">
        <input type="submit" value="ログアウト">
    </form>
    <p><img class="mypic" src="./pic_twitter_prof/<?php if ($data[0]['extension'] === 'DUMMY') {
        print $data[0]['extension'] .'.jpg';    
    } else {
        print $data[0]['user_id'] .'.' .$data[0]['extension'];
    } ?>"></p>
    <p><?php print $data[0]['user_nickname']; ?></p>
    <p><?php print $data[0]['user_name']; ?></p>
    <p><?php print $data[0]['place']; ?></p>
    <p><?php print $data[0]['prof']; ?></p>
    <form action="twitter_prof.php" method="post">
        <input type="submit" value="プロフィール変更">
    </form>
    <form action="twitter_DM_list.php" method="post">
        <input type="submit" value="ダイレクトメッセージ">
        <input type="hidden" name="back_page" value="home">
    </form>
    <form action="twitter_friend_list.php" method="post">
        <input type="submit" value="フォロー　　<?php print $follow ?>">
        <input type="hidden" name="display_function" value="follow">
        <input type="hidden" name="user_id" value="<?php print $user_id; ?>">
        <input type="hidden" name="control_id" value="<?php print $user_id; ?>">
        <input type="hidden" name="back_page" value="home">
        <input type="hidden" name="user_name" value="">
    </form>
    <form action="twitter_friend_list.php" method="post">
        <input type="submit" value="フォロワー　<?php print $follower ?>">
        <input type="hidden" name="display_function" value="follower">
        <input type="hidden" name="user_id" value="<?php print $user_id; ?>">
        <input type="hidden" name="control_id" value="<?php print $user_id; ?>">
        <input type="hidden" name="back_page" value="home">
        <input type="hidden" name="user_name" value="">
    </form>
    <form action="twitter_tweet.php" method="get">
        <input type="submit" value="ツイート　　<?php print $tweet_count; ?>">
        <input type="hidden" name="function" value="tweet">
        <input type="hidden" name="user_name" value="<?php print $data[0]['user_name']; ?>">
    </form>
    <?php if (!empty($recommended_data)) { ?>
    <caption>あなたにおすすめのユーザー</caption><br>
    <tr>
        <?php foreach ($recommended_data as $info) { ?>
            <td>
            <img class="recommended_pic" src="./pic_twitter_prof/<?php if ($info['extension'] === 'DUMMY') {
                print $info['extension'] .'.jpg';    
            } else {
                print $info['recommended_id'] .'.' .$info['extension'];
            } ?>">
            </td>
            <td><a href="twitter_tweet.php?user_name=<?php print urlencode($info['user_name']); ?>"><?php print $info['user_nickname']; ?></a></td>
            <td><form action="twitter_home.php" method="post">
                <input type="submit" value="フォロー"></td>
                <input type="hidden" name="function" value="follow">
                <input type="hidden" name="follow_id" value="<?php print $info['recommended_id']; ?>">
            </form></td>
        <?php } ?>
        </tr>
     <?php } ?>
     <?php if (!empty($hash_tag_data)) { ?>
    <caption>人気のハッシュタグ</caption><br>
    <tr>
        <?php foreach ($hash_tag_data as $info) { ?>
            <td><a href="twitter_hash_tag.php?word=<?php print urlencode($info['word']); ?>"><?php print $info['word']; ?></a></br>
            <td><?php print $info['count(twitter_hash_tag_relation.hash_tag_id)']; ?>件のツイート</td><br>
            <br>
        <?php } ?>
        </tr>
     <?php } ?>
    </div>
    <div id=tweet_box>
    <form action="twitter_home.php" method="post">
        <textarea id="tweet" name="tweet" rows="3" cols="50" wrap="hard" placeholder="今何をしている？"><?php print $reply_name; ?></textarea></br>
        <input type="submit" value="tweet">
        <input type="hidden" name="function" value="tweet">
    </form>
    <table>
    <?php if (!empty($tweet_data)) {
        foreach ($tweet_data as $tweet) { ?>
        <?php if (isset($tweet['retweet_id'])) { ?>
        <tr>
            <td colspan="3"><?php print $tweet['retweet_user_nickname']; ?>さんがリツイート</td>
        </tr>
        <?php } ?>
        <tr>
            <td><img class="pic" src="./pic_twitter_prof/<?php if ($tweet['extension'] === 'DUMMY') {
                print $tweet['extension'] .'.jpg';    
            } else {
                print $tweet['user_id'] .'.' .$tweet['extension'];
            } ?>"></td>
            <td><a href="twitter_tweet.php?user_name=<?php print urlencode($tweet['user_name']); ?>"><?php print $tweet['user_name']; ?></a></br>
            <?php print $tweet['user_nickname']; ?></td>
            <td><?php print $tweet['tweet']; ?></td>
            <td><?php print $tweet['tweet_time']; ?></td>
            <td>
                <?php if ($tweet['user_id'] === $user_id) { ?>
                <form action="twitter_home.php" method="post">
                    <input type="submit" value="削除">
                    <input type="hidden" name="delete_id" value="<?php print $tweet['tweet_id']; ?>">
                    <input type="hidden" name="function" value="delete_tweet">
                </form>
                <?php } else { if (isset($tweet['retweet_id'])) { ?>
                <form action="twitter_home.php" method="post">
                    <input type="submit" value="リツイートを取り消す">
                    <input type="hidden" name="retweet_id" value="<?php print $tweet['retweet_id']; ?>">
                    <input type="hidden" name="function" value="delete_retweet">
                </form>   
                    <?php } else { ?>
                <form action="twitter_home.php" method="post">
                    <input type="submit" value="リツイート">
                    <input type="hidden" name="tweet_id" value="<?php print $tweet['tweet_id']; ?>">
                    <input type="hidden" name="function" value="retweet">
                </form>
                <form action="twitter_home.php" method="post">
                    <input type="submit" value="返信">
                    <input type="hidden" name="receiver_name" value="<?php print $tweet['user_name']; ?>">
                    <input type="hidden" name="function" value="reply">
                </form>
                    <?php }
                } ?>
            </td>
        </tr>
        <?php }
    } else { ?>
        <div>何かつぶやいてみましょう</div>
    <?php } ?>
    </table>
    </div>
</body>
</html>