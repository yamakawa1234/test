<?php
 
$regexp_mail       = '/^[-._a-zA-Z0-9\/]+@[-._a-z0-9]+\.[a-z]{2,4}$/'; // メールアドレスの正規表現
$regexp_password   = '/[a-z0-9{6,18}/'; // パスワードの正規表現
$error_level = 0; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reslut_check_mail         = check_regexp($regexp_mail, $_POST['mail'], 'mail');
    $reslut_check_password     = check_regexp($regexp_password, $_POST['passwd'], 'password');
    
    function check_regexp($regexp, $str_data, $stats) {
 
    $msg = array();
 
    foreach ($str_data as $value) {
        //matches を指定した場合、検索結果が代入されます。 
        //$matches[0] にはパターン全体にマッチしたテキストが代入され、 
        //$matches[1] には 1 番目のキャプチャ用サブパターンにマッチした 
        //文字列が代入され、といったようになります。
        //簡単に言うと、matches[0]には正規表現でマッチングした文字が格納されている。
        //インプットとアウトプットが異なる場合は部分一致である！
        if (preg_match($regexp, $value, $macthes) === 1) {
            if ($value === $macthes[0]) {
                print '何もしない';
            } else {
                switch ($stats) {
                    case 'mail':
                        print 'メール';
                        $msg[] =  'メールアドレスの形式が正しくありません';
                        $error_level++;
                        break;
                    case 'password':
                        $msg[] =  'パスワードは半角英数記号6文字以上18文字以下で入力してください';
                        $error_level++;
                        break;
                }
            }
        } else {
            switch ($stats) {
                    case 'mail':
                        $msg[] =  'メールアドレスの形式が正しくありません';
                        $error_level++;
                        break;
                    case 'password':
                        $msg[] =  'パスワードは半角英数記号6文字以上18文字以下で入力してください';
                        $error_level++;
                        break;
            }
        }
    }
    return $msg;
}
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>課題</title>
    <style>
        .block {
            display: block;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <form method="post">
        <!--
        <label for=*>はinputのidと関連付けられる
        labelの文字をクリックすると、関連付けられたinputにカーソルが遷移する。
        -->
        <label for="mail">メールアドレス</label>
        <input type="text" class="block" id="mail" name="mail" value="">
        <label for="passwd">パスワード</label>
        <input type="password" class="block" id="passwd" name="passwd" value="">
        <?php foreach ($reslut_check_mail as $value) { ?>
                <p><?php print htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php } ?>
        <?php foreach ($reslut_check_password as $value) { ?>
                <p><?php print htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?></p>
        <?php } ?>
        <button type="submit">登録</button>
    </form>

</body>
</html>