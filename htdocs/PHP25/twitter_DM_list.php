<?php
/*
*  ダイレクトメッセージ一覧画面
*
*/
 
require_once '../../include/conf/const.php';
require_once '../../include/model/twitter_link.php';
require_once '../../include/model/twitter_DM_func.php';
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

if ($request_method === 'POST') {
    
    // SQL実行し登録データを配列で取得
    $info  = get_account_info($link, $user_id);
    
    // ユーザ名を取得できたか確認
    if (!isset($info)) {
        // ユーザ名が取得できない場合、ログアウト処理へリダイレクト
        header('Location: http://codecamp6475.lesson6.codecamp.jp/PHP25/twitter_logout.php');
        exit;
    }
    
    // DMリスト形式で取得
    $list_data = get_my_DM_list($link, $user_id);
}

// データベース切断
close_db_connect($link);
 
// ログイン済みユーザのホームページ表示
include_once '../../include/view/twitter_DM_list.php';