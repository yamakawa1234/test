<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>プロフィール変更</title>
    <style>
        .mail {
            display: block;
            margin-bottom: 10px;
        }
        input {
            display: block;
            margin-bottom: 10px;
        }
        textarea {  
            resize: none;  
        }
        .pic {
            width: 80px;
            height: 80px;
        }
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
    <form action="twitter_prof.php" method="post" enctype="multipart/form-data">
        <label for="account">アカウント名</label>
        <div><?php print $data[0]['user_name'] ?></div>
        <label for="mail">メールアドレス</label>
        <div><?php print $data[0]['user_mail'] ?></div>
        <label for="nickname">ニックネーム（こちらがtwitter for engineerで表示される名前になります）</label>
        <input type="text" id="nickName" name="nickname" value="<?php print $data[0]['user_nickname'] ?>">
        <label for="place">出身地</label>
        <input type="text" id="place" name="place" value="<?php print $data[0]['place'] ?>">
        <label for="prof">自己紹介</label></br>
        <textarea id="prof" name="prof" rows="3" cols="50" wrap="hard"><?php print $data[0]['prof'] ?></textarea></br>
        <label for="file">プロフィール画像(jpgかpng形式のみアップロード可能)</label></br>
        <div><input type="file" name="new_img"></div>
        <div><img class="pic" src="./pic_twitter_prof/<?php 
        if ($data[0]['extension'] === 'DUMMY') {
            print 'DUMMY.jpg';
        } else {    
            print $user_id .'.' .$data[0]['extension'];
        } ?>"></div>
        <label for="passwd">パスワード</label>
        <input type="password" id="passwd" name="passwd" value="<?php print $data[0]['user_password'] ?>">
        <label for="default_open_level">公開範囲</label><br>
        <select name="default_open_level">
        <option value="1" <?php if ($data[0]['default_open_level'] == 1) { print "selected"; } ?>>全体に公開</option>
        <option value="2" <?php if ($data[0]['default_open_level'] == 2) { print "selected"; } ?>>相互フォロワーのみに公開</option>
        <option value="9" <?php if ($data[0]['default_open_level'] == 9) { print "selected"; } ?>>非公開</option>
        </select>
        <input type="submit" value="変更">
        <input type="hidden" name="function" value="change">
    </form>
    <form action="twitter_home.php" method="post" >
        <input type="submit" value="戻る">
    </form>
    <form action="twitter_withdrawal.php" method="post" >
        <input id="withdrawal" type="submit" value="退会する">
    </form>
</body>
</html>