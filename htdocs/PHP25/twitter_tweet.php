<?php
/*
*  ユーザーページ
*
*/
 
require_once '../../include/conf/const.php';
require_once '../../include/model/twitter_link.php';
require_once '../../include/model/twitter_validation.php';
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
    // SQL実行し登録データを配列で取得
    $data = get_info_by_username($link, get_get_data('user_name'));
    
    // ユーザ名を取得できたか確認
    if (!isset($data)) {
        // ユーザ名が取得できない場合、ログアウト処理へリダイレクト
        header('Location: http://codecamp6475.lesson6.codecamp.jp/PHP25/twitter_logout.php');
        exit;
    }
    
    // 退会フラグ確認
    $withdrawal = withdrawal_decision($link, $data[0]['user_id']);
    
    $block_you = true;
    $check_dol ='';
    
    // 自身以外のユーザーをブロックできる
    if ((get_get_data('function') === 'block')) {
        $err_msg = user_block($link, $user_id, $data[0]['user_id']);
    }
    
    // ブロック解除
    if ((get_get_data('function') === 'unblock')) {
        $err_msg = user_unblock($link, $user_id, $data[0]['user_id']);
    }
    
    // 自身のページかどうか
    if ($user_id === $data[0]['user_id']) {
        $title_msg  = "自身のツイート";
    } else {
        $block_me  = block_decision($link, $user_id, $data[0]['user_id']);
        $block_you = block_decision($link, $data[0]['user_id'], $user_id);
        if ($block_you == false) {
            $title_msg   = "ブロックされているため、" . $data[0]['user_name'] ."さんをフォローしたり、";
            $title_msg  .= $data[0]['user_name'] ."さんのツイートを見ることはできません";
        } else {
            $title_msg  = $data[0]['user_nickname'] ."さんのツイート";
            // 公開レベル取得
            $default_open_level = get_default_open_level($link, $user_id, $data[0]['user_id']);
            switch ($default_open_level){
                case 2:
                    $check_dol = check_default_open_level($link, $user_id, $data[0]['user_id']);
                    break;
                case 9:
                    $title_msg  .= $data[0]['user_name'] ."さんのツイートは非公開のため見ることはできません";
                    break;
    }
        }
    }
    print $check_dol;
    
    // 自身のページを参照している場合、自身のツイートを削除できる
    if ((get_get_data('function') === 'delete') and ($user_id === $data[0]['user_id'])) {
        $err_msg = delete_tweet($link, get_get_data('delete_id'), $data[0]['user_id']);
    }
    
    // このユーザーをフォロー
    if ((get_get_data('function') === 'follow')) {
        $err_msg = follow($link, $user_id, $data[0]['user_id']);
    }
    // このユーザーをアンフォロー
    if ((get_get_data('function') === 'unfollow')) {
        $err_msg = unfollow($link, $user_id, $data[0]['user_id']);
    }
    
    if ($block_you == true) {
        // tweet全件取得
        $tweet_data = get_my_tweet($link, $data[0]['user_id']);
    }
    
    // フォロー状態確認
    $follow_status = check_follow_status($link, $user_id, $data[0]['user_id']);
    // フォロー数取得
    $follow = count(display_follow_or_follower_mine($link, $data[0]['user_id'], 'follow'));
    // フォロワー数取得
    $follower = count(display_follow_or_follower_mine($link, $data[0]['user_id'], 'follower'));
    // 自身のツイート数取得
    $tweet_count = count(get_my_tweet($link, $data[0]['user_id']));
    
}

// データベース切断
close_db_connect($link);
 
// ログイン済みユーザのホームページ表示
include_once '../../include/view/twitter_tweet.php';