<?php
 
// 設定ファイル読み込み
require_once '../../include/conf/const.php';
// 関数ファイル読み込み
require_once '../../include/model/function.php';
 
// 変数初期化
$goods_name = '';      // 商品名
$price      = '';      // 価格
$err_msg    = array(); // エラーメッセージ用配列
 
// リクエストメソッド取得
$request_method = get_request_method();
 
if ($request_method === 'POST') {
 
    // POST値取得
    $goods_name = get_post_data('goods_name');
    $price      = get_post_data('price');
 
    //////////////////////////////////////////////
    // 本来はここで商品名や価格の入力値チェックを行います。
    // MVCモデル理解優先のため省略しています。
    //////////////////////////////////////////////
 
    // DB接続
    $link = get_db_connect();
 
    // 新規商品追加
    if (insert_goods_table($link, $goods_name, $price) !== TRUE) {
        $err_msg[] = 'INSERT失敗';
    }
 
    // DB切断
    close_db_connect($link);
 
    // 特殊文字を変換
    $goods_name = entity_str($goods_name);
    $price      = entity_str($price);
 
    if (count($err_msg) === 0) {
        // 新規追加完了テンプレートファイル読み込み
        include_once '../../include/view/goods_insert_result.php';
        exit;
    }
 
}
 
// 新規追加テンプレートファイル読み込み
include_once '../../include/view/goods_insert.php';
