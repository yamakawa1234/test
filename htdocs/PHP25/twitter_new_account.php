<?php
/*
*  ログインページ
*
*/
 
require_once '../../include/conf/const.php';
require_once '../../include/model/twitter_link.php';
require_once '../../include/model/twitter_validation.php';

$registration_err_flag = false;

$account    = '';
$mail       = '';
$nickName   = '';
$password   = '';

// リクエストメソッド取得
$request_method = get_request_method();

if ($request_method === 'POST') {
    $account    = get_post_data('account');
    $mail       = get_post_data('mail');
    $nickName   = get_post_data('nickName');
    $password   = get_post_data('password');
}

include_once '../../include/view/twitter_new_account.php';