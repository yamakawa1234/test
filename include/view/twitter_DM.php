<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ダイレクトメール</title>
    <style>
    .image img{
        border-radius: 100px;
        /*今回はchrome,Firefoxは使用しないのでコメントアウト*/
        /*-webkit-border-radius: 100px;*/
        /*-moz-border-radius: 100px;*/
    }
    .pic {
        width: 25px;
        height: 25px;
    }
    </style>
</head>
<body>
    <?php if (!empty($err_msg)) {
        foreach ($err_msg as $print)
        print('<span>' .$print .'</span><br />'); // エラーメッセージ
    } ?>
    <div>
        <div>
            <div class="image"><img class="pic" src="./pic_twitter_prof/<?php 
            if ($info_you[0]['extension'] === 'DUMMY') {
                print $info_you[0]['extension'] .'.jpg'; 
            } else { 
                print $info_you[0]['user_id'] .'.' .$info_you[0]['extension']; 
            } ?>"><?php print $info_you[0]['user_nickname']; ?></div>
        </div>
        <table>
            <?php if (!empty($DM_data)) {
                foreach ($DM_data as $row) { ?>
            <tr>
                <td><div class="image"><img class="pic" src="./pic_twitter_prof/<?php if ($row['extension'] === 'DUMMY') {
                    print $row['extension'] .'.jpg'; 
                } else {
                    print $row['sender_id'] .'.' .$row['extension']; 
                } ?>"></div></td>
                <td><?php print $row['user_nickname']; ?></td>
                <td><?php print $row['message']; ?></td>
                <td><?php print $row['send_date']; ?></td>
                <?php if ($user_id === $row['sender_id']) { ?>
                <td><form action="twitter_DM.php" method="get">
                    <input type="submit" value="削除">
                    <input type="hidden" name="function" value="delete">
                    <input type="hidden" name="back_page" value="<?php print $back_page; ?>">
                    <input type="hidden" name="receive_id" value="<?php print $receive_id; ?>">
                    <input type="hidden" name="user_name" value="<?php print $user_name; ?>">
                    <input type="hidden" name="DM_id" value="<?php print $row['DM_id']; ?>">
                </form></td>
                <?php } ?>
            </tr>
            <?php 
                }
            } ?>
        </table>
        <?php if ($withdrawal == true) {  ?>
        <form action="twitter_DM.php" method="get">
            <textarea id="msg" name="msg" rows="10" cols="50" wrap="hard" placeholder="ここにメッセージを入力してください"></textarea></br>
            <input type="submit" value="送信">
            <input type="hidden" name="function" value="send">
            <input type="hidden" name="back_page" value="<?php print $back_page; ?>">
            <input type="hidden" name="receive_id" value="<?php print $receive_id; ?>">
            <input type="hidden" name="user_name" value="<?php print $user_name; ?>">
        </form>
        <?php } else { ?>
        <div>このユーザーはすでに退会しています</div>
        <?php } ?>
    </div>
    <?php if ($back_page === 'tweet') { ?>
        <form action="twitter_tweet.php" method="get">
            <input type="submit" value="戻る">
            <input type="hidden" name="user_name" value="<?php print $user_name; ?>">
        </form>    
    <?php } ?>
    <?php if ($back_page === 'DM_list') { ?>
        <form action="twitter_DM_list.php" method="post">
            <input type="submit" value="戻る">
        </form>
    <?php } ?>
</body>
</html>