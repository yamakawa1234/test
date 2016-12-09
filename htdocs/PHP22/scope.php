
<?php/*
$str = 'スコープテスト'; // 関数外で変数定義(グローバル変数)
 
function test_scope() {
    print $str; // 関数内の変数を参照
}
 
test_scope();
*/?>

<?php/*
function test_scope() {
    $str = 'スコープテスト'; // ローカル変数の定義
}
 
test_scope();
print $str; // グローバル変数の参照
*/?>

<?php
$str = 'スコープテスト'; // グローバル変数
 
function test_scope() {
    global $str; // グローバル宣言(グローバル変数を参照)
    print $str;
}
 
test_scope();
?>
