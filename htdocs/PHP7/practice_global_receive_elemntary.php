<?php
$name = ''; //変換元
$nameCh = ''; //変換先
if (isset($_POST['send']) === TRUE) {
    if (isset($_POST['name']) === TRUE) {
        $name = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
        //全角スペースを半角スペースに変換
        $nameCh = mb_convert_kana($name, 's', 'UTF-8');
        //ctype_spaceは半角スペースしか適応されないので注意
        if ((0 == strlen($nameCh))||(ctype_space($nameCh))) {
?>            
            <p>名前を入力してください</p>
            
<?php            
        } else {
?>
            <p>ようこそ　<?php print $nameCh; ?>さん</p>
<?php            
        }
    }
}
?>