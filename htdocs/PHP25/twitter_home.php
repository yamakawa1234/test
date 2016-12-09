<?php
/*
*  ログイン済みユーザのホームページ
*
*/
 
require_once '../../include/conf/const.php';
require_once '../../include/model/twitter_link.php';
require_once '../../include/model/twitter_validation.php';
require_once '../../include/model/twitter_friend.php';
require_once '../../include/model/twitter_tweet_func.php';
require_once '../../include/model/twitter_my_account.php';
require_once '../../include/model/twitter_block.php';
 
// セッション開始
session_start();
 
// セッション変数からuser_id取得
if (isset($_SESSION['user_id']) === TRUE) {
    $user_id = $_SESSION['user_id'];
} else {
    // 非ログインの場合、ログインページへリダイレクト
    header('Location: http://' . $_SERVER['HTTP_HOST'] . '//PHP25/twitter_top.php');
    exit;
}
 
// データベース接続
$link = get_db_connect();

// user_idからユーザ名を取得するSQL
$data = get_account_info($link, $user_id);

// フォロー数取得
$follow = count(display_follow_or_follower_mine($link, $user_id, 'follow'));
// フォロワー数取得
$follower = count(display_follow_or_follower_mine($link, $user_id, 'follower'));
// 自身のツイート数取得
$tweet_count = count(get_my_tweet($link, $user_id));

// ユーザ名を取得できたか確認
if (!isset($data)) {
    // ユーザ名が取得できない場合、ログアウト処理へリダイレクト
    header('Location: http://codecamp6475.lesson6.codecamp.jp/PHP25/twitter_logout.php');
    exit;
}
// リクエストメソッド取得
$request_method = get_request_method();

$tweet = trim_and_entity(get_post_data('tweet'));
$reply_name = '';
 
if ($request_method === 'POST') {
    // insert
    if (get_post_data('function') === 'tweet') {
        $err_msg = tweet_validation($tweet);
        if (empty($err_msg)) {
            $err_msg = insert_tweet($link, $user_id, $tweet);
        }
        if (empty($err_msg)) {
            $tweet_id = mysqli_insert_id($link);
            $err_msg = tweet_receive($link, $tweet, $user_id, $tweet_id);
        }
        if (empty($err_msg)) {
            $err_msg = registration_hash_tag($link, $tweet, $tweet_id);
        }
    }
    // retweet
    if (get_post_data('function') === 'retweet') {
        $err_msg = insert_retweet($link, $user_id, get_post_data('tweet_id'));
    }
    // delete
    if (get_post_data('function') === 'delete_tweet') {
        $err_msg = delete_tweet($link, get_post_data('delete_id'), $user_id);
    }
    // retweetのdelete
    if (get_post_data('function') === 'delete_retweet') {
        $err_msg = delete_retweet($link, get_post_data('retweet_id'));
    }
    // おすすめユーザーのフォロー
    if (get_post_data('function') === 'follow') {
        $err_msg = follow($link, $user_id, get_post_data('follow_id'));
    }
    // 返信先名をツイートボックスに格納
    if (get_post_data('function') === 'reply') {
        $reply_name = reply_name($link, get_post_data('receiver_name'));
    }
}
// tweet全件取得
$tweet_data = get_tweet($link, $user_id);

// おすすめユーザー取得
$recommended_data = display_recommended_user($link, $user_id);

// 人気のハッシュタグを取得
$hash_tag_data = get_trend($link, 7);

// データベース切断
close_db_connect($link);
 
// ログイン済みユーザのホームページ表示
include_once '../../include/view/twitter_home.php';