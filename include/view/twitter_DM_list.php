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
        float:left;
    }
    .clearfix:after {
        content: "."; 
        display: block; 
        height: 0; 
        font-size:0;	
        clear: both; 
        visibility:hidden;
    }
    .clearfix{
        display: inline-block;
    } 
    .block {
        display:block;
        width:1000px;
    　  border: 1px solid #c7c7bc; 
    　  color: #404040; 
    }
    a {
    　display:block;
    　width:100%;
      height:100%;
 　  } 
    </style>
</head>
<body>
    <?php if (!empty($err_msg)) {
        foreach ($err_msg as $print)
        print('<span>' .$print .'</span><br />'); // エラーメッセージ
    } ?>
    <div>
            <?php if (!empty($list_data)) {
                foreach ($list_data as $row) { ?>
                <div class=block>
                <a href="twitter_DM.php?receive_id=<?php print urlencode($row[0]['receiver_id']); ?>&back_page=DM_list">
                <?php if ($user_id === $row[0]['sender_id']) { ?>
                <span><div class="image"><img class="pic" src="./pic_twitter_prof/<?php if ($row[0]['receiver_extension'] === 'DUMMY') {
                    print $row[0]['receiver_extension'] .'.jpg'; 
                } else {
                    print $row[0]['receiver_id'] .'.' .$row[0]['receiver_extension']; 
                } ?>"></div></span>
                <span><?php print $row[0]['receiver_nickname']; ?></span>
                <span><?php print $row[0]['message']; ?></span></br>
                <span><?php print $row[0]['send_date']; ?></span>
                </div>
                <?php } ?>
                
                <?php if ($user_id === $row[0]['receiver_id']) { ?>
                <div class=block>
                <a href="twitter_DM.php?receive_id=<?php print urlencode($row[0]['sender_id']); ?>&back_page=DM_list">
                <span><div class="image"><img class="pic" src="./pic_twitter_prof/<?php if ($row[0]['sender_extension'] === 'DUMMY') {
                    print $row[0]['sender_extension'] .'.jpg'; 
                } else {
                    print $row[0]['sender_id'] .'.' .$row[0]['sender_extension']; 
                } ?>"></div></span>
                <span><?php print $row[0]['sender_nickname']; ?></span>
                <span><?php print $row[0]['message']; ?></span></br>
                <span><?php print $row[0]['send_date']; ?></span>
                </div>
                <?php } ?>
                <div class=clearfix></div>
            <?php 
                }
            } ?>
        </table>
    </div>
    <form action="twitter_home.php" method="post" >
        <input type="submit" value="戻る">
    </form>
</body>
</html>