<?php
/*
*  ログイン済みユーザのホームページ
*  プロフィール編集
*/
 
require_once '../../include/conf/const.php';
require_once '../../include/model/twitter_link.php';
require_once '../../include/model/twitter_validation.php';
require_once '../../include/model/twitter_my_account.php';
 
// セッション開始
session_start();
 
// セッション変数からuser_id取得
if (isset($_SESSION['user_id']) === TRUE) {
    $user_id = $_SESSION['user_id'];
} else {
    // 非ログインの場合、ログインページへリダイレクト
    header('Location: http://codecamp6475.lesson6.codecamp.jp//PHP25/twitter_top.php');
    exit;
}
 
// データベース接続
$link = get_db_connect();

// リクエストメソッド取得
$request_method = get_request_method();

$nickname   = trim_and_entity(get_post_data('nickname'));
$password   = trim_and_entity(get_post_data('passwd'));
$place      = trim_and_entity(get_post_data('place'));
$prof       = trim_and_entity(get_post_data('prof'));
$tmp_name   = '';

if ($request_method === 'POST') {
    if (get_post_data('function') === 'change') {
        
        if (is_uploaded_file($_FILES["new_img"]["tmp_name"])) {
            $tmp_name   = $_FILES["new_img"]["tmp_name"];
        }
        $err_msg = change_validation($link, $nickname, $password, $place, $prof, $tmp_name);
        if (empty($err_msg)) {
            //$err_msg = update_info($link, $user_id, $nickname, $password, $place, $prof, $tmp_name);
            $err_msg = update_info($link, $user_id, $nickname, $password, $place, $prof, $tmp_name, get_post_data('default_open_level'));
        }
    }
}

// user_idからユーザ名を取得するSQL
$data = get_account_info($link, $user_id);
 
// ユーザ名を取得できたか確認
if (!isset($data)) {
    // ユーザ名が取得できない場合、ログアウト処理へリダイレクト
    header('Location: http://codecamp6475.lesson6.codecamp.jp/PHP25/twitter_logout.php');
    exit;
}

// データベース切断
close_db_connect($link);
 
// ログイン済みユーザのホームページ表示
include_once '../../include/view/twitter_prof.php';