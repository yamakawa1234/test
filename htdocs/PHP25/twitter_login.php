<?php
/*
*  ログイン処理
*
*/
 
require_once '../../include/conf/const.php';
require_once '../../include/model/twitter_link.php';
require_once '../../include/model/twitter_validation.php';
require_once '../../include/model/twitter_my_account.php';
 
// リクエストメソッド確認
if (get_request_method() !== 'POST') {
    // POSTでなければログインページへリダイレクト
    header('Location: http://codecamp6475.lesson6.codecamp.jp//PHP25/twitter_top.php');
    exit;
}
 
// セッション開始
session_start();
 
// POST値取得
$account  = get_post_data('account');   // アカウントID or メールアドレス
$passwd   = get_post_data('passwd');    // パスワード
 
// データベース接続
$link = get_db_connect();

$login = login($link, $account, $passwd);

// データベース切断
close_db_connect($link);

// 登録データを取得できたか確認
if ($login == true) {
    // ログイン済みユーザのホームページへリダイレクト
    header('Location: http://codecamp6475.lesson6.codecamp.jp//PHP25/twitter_home.php');
    exit;
 
} else {
 
    // セッション変数にログインのエラーフラグを保存
    // $_SESSION['login_err_flag'] = TRUE;
    // ログインページへリダイレクト
    header('Location: http://codecamp6475.lesson6.codecamp.jp//PHP25/twitter_top.php');
    exit;
 
}
