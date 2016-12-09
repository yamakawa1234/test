<?php
/*
*  配列$areasは長いので手打ちせずコピーして利用してください。
*/
$class = array('ガリ勉' => '鈴木', '委員長' => '佐藤', 'セレブ' => '斎藤', 'メガネ' => '伊藤', '女神' => '杉内');

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>foreachの使用例</title>
</head>
<body>

<?php
// 都道府県の配列をループさせる
foreach ($class as $key => $area) {
?>
            <p><?php print $area; ?>さんのアダ名は<?php print $key; ?>です。</p>
<?php
}
?>
</body>
</html>