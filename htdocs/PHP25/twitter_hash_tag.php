<?php
/*
*  ユーザーページ
*
*/
 
require_once '../../include/conf/const.php';
require_once '../../include/model/twitter_link.php';
require_once '../../include/model/twitter_friend.php';
require_once '../../include/model/twitter_my_account.php';
require_once '../../include/model/twitter_tweet_func.php';
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
    
    // ハッシュタグを含むtweet全件取得
    $hash_tag_data = display_hash_tag($link, $user_id, get_get_data('word'));
}

// データベース切断
close_db_connect($link);
 
// ログイン済みユーザのホームページ表示
include_once '../../include/view/twitter_hash_tag.php';