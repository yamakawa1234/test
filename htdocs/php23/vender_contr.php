<?php

// 設定ファイル読み込み
require_once '../../include/conf/const.php';
// 関数ファイル読み込み
require_once '../../include/model/vender_function.php';
 
$err_msg         = array(); // エラーメッセージ
$vending_info    = array(); // ドリンク情報

// DB接続
$link = get_db_connect();

// リクエストメソッド取得
$request_method = get_request_method();
    /**
     * 商品情報を取得
     */
    $vending_info = get_db_vender($link);
    if (empty($vending_info)) {
        $err_msg[] = '読み込み失敗';
    }
    close_db_connect($link);

if (!empty($err_msg)) {
    foreach ($err_msg as $print)
    print($print .'<br />'); // エラーメッセージ
}

// ファイル読み込み
include_once '../../include/view/vender.php';