<?php
/*
*  ログインページ
*
*/
 
require_once '../../include/conf/const.php';
require_once '../../include/model/twitter_link.php';
require_once '../../include/model/twitter_validation.php';
 
// セッション開始
session_start();
 
// セッション変数からログイン済みか確認
if (isset($_SESSION['user_id']) === TRUE) {
    // ログイン済みの場合、ホームページへリダイレクト
    header('Location: http://codecamp6475.lesson6.codecamp.jp//PHP25/twitter_home.php');
    exit;
}
 
// セッション変数からログインエラーフラグを確認
if (isset($_SESSION['login_err_flag']) === TRUE) {
 
    // ログインエラーフラグ取得
    $login_err_flag = $_SESSION['login_err_flag'];
    // エラー表示は1度だけのため、フラグをFALSEへ変更
    $_SESSION['login_err_flag'] = FALSE;
 
} else {
    // セッション変数が存在しなければエラーフラグはFALSE
    $login_err_flag = FALSE;
}
 
// Cookie情報からメールアドレスを取得
if (isset($_COOKIE['account']) === TRUE) {
    $account = $_COOKIE['account'];
} else {
    $account = '';
}
 
// 特殊文字をHTMLエンティティに変換
$account = trim_and_entity($account);
 
include_once '../../include/view/twitter_top.php';