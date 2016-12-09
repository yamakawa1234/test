<?php
// 変数初期化
$handMe = ''; //自分
$handOp = ''; //相手
$result = '';
//1:グー
//2:チョキ
//3:パー
if (isset($_POST['handType']) === TRUE) {
    $handMe = htmlspecialchars($_POST['handType'], ENT_QUOTES, 'UTF-8');
    $ran = mt_rand(1, 3);
    if (isset($_POST['handType']) === TRUE) {
        switch ($ran) {
            case 1:
                $handOp = 'グー';
                break;
            case 2:
                $handOp = 'チョキ';
                break;
            case 3:
                $handOp = 'パー';
                break;
        }
        //あいこの時はif文で処理したほうが文が短くなる
        switch ($handMe) {
            case 'グー':
                switch ($handOp) {
                    case 'グー':
                        $result = 'draw';
                        break;
                    case 'チョキ':
                        $result = 'win!';
                        break;
                    case 'パー':
                        $result = 'lose...';
                        break;
                } 
                break;
            case 'チョキ':
                switch ($handOp) {
                    case 'グー':
                        $result = 'lose...';
                        break;
                    case 'チョキ':
                        $result = 'draw';
                        break;
                    case 'パー':
                        $result = 'win!';
                        break;
                } 
                break;
            case 'パー':
                switch ($handOp) {
                    case 'グー':
                        $result = 'win!';
                        break;
                    case 'チョキ':
                        $result = 'lose...';
                        break;
                    case 'パー':
                        $result = 'draw';
                        break;
                }
                break;    
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>practice_intermediate</title>
</head>
<body>
    <h2>じゃんけん勝負</h2>
    <p>自分：<?php print $handMe; ?></p>
    <p>相手：<?php print $handOp; ?></p>
    <p>結果：<?php print $result; ?></p>
    <form method="post">
        <label><input type="radio" name="handType" value="グー" <?php if ($handMe === 'グー') { print 'checked'; } ?>>グー
        <input type="radio" name="handType" value="チョキ" <?php if ($handMe === 'チョキ') { print 'checked'; } ?>>チョキ
        <input type="radio" name="handType" value="パー" <?php if ($handMe === 'パー') { print 'checked'; } ?>>パー</label>
        <input type="submit" value="勝負！！">
    </form>
</body>
</html>