<?php
/*
*  ログイン処理
*
*  セッションの仕組み理解を優先しているため、一部処理はModelへ分離していません
*  また処理はセッション関連の最低限のみ行っており、本来必要な処理も省略しています
*/
 
require_once '../../include/conf/const.php';
require_once '../../include/model/function.php';
 
// リクエストメソッド確認
if (get_request_method() !== 'POST') {
    // POSTでなければログインページへリダイレクト
    header('Location: http://codecamp.lesson.codecamp.jp/php24/session_sample_top.php');
    exit;
}
 
// セッション開始
session_start();
 
// POST値取得
$email  = get_post_data('email');  // メールアドレス
$passwd = get_post_data('passwd'); // パスワード
 
// メールアドレスをCookieへ保存
setcookie('email', $email, time() + 60 * 60 * 24 * 365);
 
// データベース接続
$link = get_db_connect();
 
// メールアドレスとパスワードからuser_idを取得するSQL
$sql = 'SELECT user_id FROM user_table
        WHERE email =\'' . $email . '\' AND passwd =\'' . $passwd . '\'';
 
// SQL実行し登録データを配列で取得
$data = get_as_array($link, $sql);
 
// データベース切断
close_db_connect($link);
 
// 登録データを取得できたか確認
if (isset($data[0]['user_id'])) {
 
    // セッション変数にuser_idを保存
    $_SESSION['user_id'] = $data[0]['user_id'];
    // ログイン済みユーザのホームページへリダイレクト
    header('Location: http://codecamp6475.lesson6.codecamp.jp/php24/session_sample_home.php');
    exit;
 
} else {
 
    // セッション変数にログインのエラーフラグを保存
    $_SESSION['login_err_flag'] = TRUE;
    // ログインページへリダイレクト
    header('Location: http://codecamp6475.lesson6.codecamp.jp/php24/session_sample_top.php');
    exit;
 
}
