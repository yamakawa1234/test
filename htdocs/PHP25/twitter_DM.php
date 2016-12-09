<?php
/*
*  ダイレクトメッセージ画面
*
*/
 
require_once '../../include/conf/const.php';
require_once '../../include/model/twitter_link.php';
require_once '../../include/model/twitter_validation.php';
require_once '../../include/model/twitter_DM_func.php';
require_once '../../include/model/twitter_my_account.php';
require_once '../../include/model/twitter_friend.php';
require_once '../../include/model/twitter_block.php';
 
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

if ($request_method === 'GET') {
    // 引き継ぎ項目
    $back_page  = get_get_data('back_page');
    $receive_id = get_get_data('receive_id');
    $user_name  = get_get_data('user_name');
    
    // SQL実行し登録データを配列で取得
    $info_me  = get_account_info($link, $user_id);
    $info_you = get_account_info($link, $receive_id);
    
    // ユーザ名を取得できたか確認
    if (!isset($info_me) or !isset($info_you)) {
        // ユーザ名が取得できない場合、ログアウト処理へリダイレクト
        header('Location: http://codecamp6475.lesson6.codecamp.jp/PHP25/twitter_logout.php');
        exit;
    }
    
    // 退会フラグ確認
    $withdrawal = withdrawal_decision($link, $receive_id);
    
    if (get_get_data('function') === 'send') {
        $err_msg = send_DM($link, $user_id, $receive_id, get_get_data('msg'));
    }
    
    if (get_get_data('function') === 'delete') {
        $err_msg = delete_DM($link, $user_id, $receive_id, get_get_data('DM_id'));
    }
    
    // DM全件取得
    $DM_data = get_DM($link, $user_id, $receive_id);
}

// データベース切断
close_db_connect($link);
 
// ログイン済みユーザのホームページ表示
include_once '../../include/view/twitter_DM.php';