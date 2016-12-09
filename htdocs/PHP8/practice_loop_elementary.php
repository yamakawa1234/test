<?php
$table = 0; //表
$back  = 0; //裏
$loop  = 0;
if (isset($_POST['number']) === TRUE) {
    $table = 0; 
    $back  = 0; 
    $loop = $_POST['number'];
    for ($i = 1; $i <= $loop; $i++) {
        $ran = mt_rand(1, 2); //1:表 2:裏
        if ($ran === 1) {
            $table = $table + 1;
        } else {
            $back = $back + 1;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>課題</title>
</head>
<body>
    <article id="wrap">
        <p><?php print $loop;?></p>
        <section>
            <p>表: <?php print $table; ?>回</p>
            <p>裏: <?php print $back; ?>回</p>
        </section>
        <form method="post" action="">
            <select name="number">
                <option value="">回数選択</option>
                <option value="10">10</option>
                <option value="100">100</option>
                <option value="1000">1000</option>
            </select>回
            <button type="submit">コイントス</button>
        </form>
    </article>
</body>
</html>
