<?php

// 設定ファイル読み込み
require_once '../../include/conf/const.php';
// 関数ファイル読み込み
require_once '../../include/model/vender_function.php';

$msg = array(); // 通常出力メッセージ
$err_msg = array(); // エラーメッセージ
$row     = array(); // ドリンク情報

// DB接続
$link = get_db_connect();

// リクエストメソッド取得
$request_method = get_request_method();

if ($request_method === 'POST') {

    // 現在時刻を取得
    $date = get_date();
    
    $err_msg = validation_result($link, get_post_data('money'), get_post_data('drink_id'));

} else {
    $err_msg[] = 'ページ遷移ミス';
}
// var_dump($err_msg);  // デバック用
if (empty($err_msg)) {
    $row = get_db_match2($link, get_post_data('money'), get_post_data('drink_id'), $date);
    $err_msg = get_db_match3($link, get_post_data('money'), get_post_data('drink_id'), $date, $row);
    
}

// ファイル読み込み
include_once '../../include/view/result.php';