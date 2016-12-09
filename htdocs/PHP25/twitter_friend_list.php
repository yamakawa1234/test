<?php
/*
*  ログイン済みユーザのホームページ
*
*/
 
require_once '../../include/conf/const.php';
require_once '../../include/model/twitter_link.php';
require_once '../../include/model/twitter_validation.php';
require_once '../../include/model/twitter_friend.php';
 
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
    header('Location: http://codecamp6475.lesson6.codecamp.jp//PHP25/twitter_home.php');
    exit;
}

// リクエストメソッド取得
$request_method = get_request_method();

if ($request_method === 'POST') {
    $control_id = get_post_data('control_id');
    $back_page  = get_post_data('back_page');
    $user_name  = get_post_data('user_name');

    if ($user_id === get_post_data('control_id')) {
        // 自身のフォロー＆フォロワーを参照する処理
        if (get_post_data('function') === 'follow') {
            if (get_post_data('display_function') === 'follow') {
                $err_msg = follow($link, get_post_data('followed_id'), $user_id);
            }
            if (get_post_data('display_function') === 'follower') {
                $err_msg = follow($link, $user_id, get_post_data('user_id'));
            }
        }
        
        if ((get_post_data('function') === 'unfollow') and 
            (get_post_data('display_function') === 'follower')) {
            $err_msg = unfollow($link, $user_id, get_post_data('user_id'));
        }
        
        if ((get_post_data('function') === 'unfollow') and 
            (get_post_data('display_function') === 'follow')) {
            $err_msg = unfollow($link, $user_id, get_post_data('followed_id'));
        }
        
        if( get_post_data('display_function') === 'follower' || get_post_data('display_function') === 'follow' ){
            $data = display_follow_or_follower_mine($link, $user_id, get_post_data('display_function'));
            if (count($data) === 0) {
                $err_msg[] = 'まだ誰もいません';
            }
        }
    } else {
        // 他人のページからフォロー＆フォロワーを参照する処理
        if (get_post_data('function') === 'follow') {
            if (get_post_data('display_function') === 'follow') {
                $err_msg = follow($link, $user_id, get_post_data('followed_id'));
            }
            if (get_post_data('display_function') === 'follower') {
                $err_msg = follow($link, $user_id, get_post_data('user_id'));
            }
        }
        
        if ((get_post_data('function') === 'unfollow') and 
            (get_post_data('display_function') === 'follower')) {
            $err_msg = unfollow($link, $user_id, get_post_data('user_id'));
        }
        
        if ((get_post_data('function') === 'unfollow') and 
            (get_post_data('display_function') === 'follow')) {
            $err_msg = unfollow($link, $user_id, get_post_data('followed_id'));
        }
        
        if( get_post_data('display_function') === 'follower' || get_post_data('display_function') === 'follow' ){
            $data = display_follow_or_follower($link, $user_id, get_post_data('control_id'), get_post_data('display_function'));
            if (count($data) === 0) {
                $err_msg[] = 'まだ誰もいません';
            }
        }
    }
    
} else {
    $err_msg[] = 'ページ遷移ミス';
}

// データベース切断
close_db_connect($link);
 
// ログイン済みユーザのホームページ表示
include_once '../../include/view/twitter_friend_list.php';