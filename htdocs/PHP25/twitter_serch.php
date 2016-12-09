<?php
/*
*  ログイン済みユーザのホームページ
*
*/
 
require_once '../../include/conf/const.php';
require_once '../../include/model/twitter_link.php';
require_once '../../include/model/twitter_validation.php';
require_once '../../include/model/twitter_friend.php';
require_once '../../include/model/twitter_block.php';

$data = array();
 
// データベース接続
$link = get_db_connect();
 
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

// リクエストメソッド取得
$request_method = get_request_method();

if ($request_method === 'POST') {
    
    $back_page  = get_post_data('back_page');
    $user_name  = get_post_data('user_name');
    
    // 入力文字列をトリム
    $serch    = trim_and_entity(get_post_data('serch'));
    
    // 入力した値がブランクの場合、エラー
    if (empty($serch)) {
        $err_msg[] = '検索ワードを入力してください';
    }
    
} else {
    $err_msg[] = 'ページ遷移ミス';
}
if (empty($err_msg)) {
    if (get_post_data('function') === 'follow' || get_post_data('function') === 'unfollow') {
        $err_msg = follow_or_unfollower($link, $user_id, get_post_data('followed_id'), get_post_data('function'));
    }
     
    $data = serch_friend($link, $serch, $user_id);
    
    if (empty($data)) {
        $err_msg[] = '何もヒットしませんでした';
        $err_msg[] = '検索ワードをもう一度ご確認ください';
    }
}

// データベース切断
close_db_connect($link);

// ログイン済みユーザのホームページ表示
include_once '../../include/view/twitter_serch.php';