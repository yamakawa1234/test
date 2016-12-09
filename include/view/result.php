<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>自動販売機結果</title>
    <style>
    .pict {
            width: 100px;
            height: 120px;
        }
    </style>
</head>
<body>
    <h1>自動販売機結果</h1>
    <section>
        <?php if (!empty($msg)) {
            foreach ($msg as $print)
            print('<span>' .$print .'</span><br />'); // 通常のコメント
        } ?>
        <?php if (!empty($err_msg)) {
            foreach ($err_msg as $print)
            print('<span>' .$print .'</span><br />'); // エラーメッセージ
        } ?>
    </section>
    <a href="vender_contr.php">戻る</a>
</body>
</html>