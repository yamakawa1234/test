<?php
/*
*  新規会員登録ページ
*
*/
 
require_once '../../include/conf/const.php';
require_once '../../include/model/twitter_link.php';
require_once '../../include/model/twitter_validation.php';
require_once '../../include/model/twitter_my_account.php';
 
// データベース接続
$link = get_db_connect();
 
// リクエストメソッド取得
$request_method = get_request_method();

if ($request_method === 'POST') {
    
    // 入力文字列をトリム
    $account    = trim_and_entity(get_post_data('account'));
    $mail       = trim_and_entity(get_post_data('mail'));
    $nickName   = trim_and_entity(get_post_data('nickName'));
    $password   = trim_and_entity(get_post_data('passwd'));
    
    $err_msg = registration_validation($link, $account, $mail, $nickName, $password);
    
} else {
    $err_msg[] = 'ページ遷移ミス';
}

if (empty($err_msg)) {
    $err_msg = insert_account($link, $account, $mail, $nickName, $password);
}

// データベース切断
close_db_connect($link);
 
// ログイン済みユーザのホームページ表示
include_once '../../include/view/twitter_registration.php';