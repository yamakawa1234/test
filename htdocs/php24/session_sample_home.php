<?php
/*
*  ログイン済みユーザのホームページ
*
*  セッションの仕組み理解を優先しているため、一部処理はModelへ分離していません
*  また処理はセッション関連の最低限のみ行っており、本来必要な処理も省略しています
*/
 
require_once '../../include/conf/const.php';
require_once '../../include/model/function.php';
 
// セッション開始
session_start();
 
// セッション変数からuser_id取得
if (isset($_SESSION['user_id']) === TRUE) {
    $user_id = $_SESSION['user_id'];
} else {
    // 非ログインの場合、ログインページへリダイレクト
    header('Location: http://codecamp6475.lesson6.codecamp.jp/php24/session_sample_top.php');
    exit;
}
 
// データベース接続
$link = get_db_connect();
 
// user_idからユーザ名を取得するSQL
$sql = 'SELECT user_name FROM user_table WHERE user_id = ' . $user_id;
 
// SQL実行し登録データを配列で取得
$data = get_as_array($link, $sql);
 
// データベース切断
close_db_connect($link);
 
// ユーザ名を取得できたか確認
if (isset($data[0]['user_name'])) {
    $user_name = $data[0]['user_name'];
} else {
    // ユーザ名が取得できない場合、ログアウト処理へリダイレクト
    header('Location: http://codecamp6475.lesson6.codecamp.jp/php24/session_sample_logout.php');
    exit;
}
 
// ログイン済みユーザのホームページ表示
include_once '../../include/view/session_sample_home.php';