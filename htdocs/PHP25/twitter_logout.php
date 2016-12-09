<?php
/*
*  ログアウト処理
*
*  セッションの仕組み理解を優先しているため、一部処理はModelへ分離していません
*  また処理はセッション関連の最低限のみ行っており、本来必要な処理も省略しています
*/
 
require_once '../../include/conf/const.php';
require_once '../../include/model/twitter_link.php';
require_once '../../include/model/twitter_validation.php';
require_once '../../include/model/twitter_my_account.php';
 
// セッション開始
session_start();
 
// セッション名取得 ※デフォルトはPHPSESSID
$session_name = session_name();

// ログアウト処理
$logout = logout($link, $session_name);

if ($logout == true) {
    // セッションIDを無効化
    session_destroy();
    
    // ログアウトの処理が完了したらログインページへリダイレクト
    header('Location: http://codecamp6475.lesson6.codecamp.jp//PHP25/twitter_top.php');
    exit;
} else {
    print 'ログアウト処理失敗';
}