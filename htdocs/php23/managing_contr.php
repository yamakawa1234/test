<?php

// 設定ファイル読み込み
require_once '../../include/conf/const.php';
// 関数ファイル読み込み
require_once '../../include/model/vender_function.php';
 
// 変数初期化
$err_msg    = array(); // エラーメッセージ用配列

// DB接続
$link = get_db_connect();

// リクエストメソッド取得
$request_method = get_request_method();
 
if ($request_method === 'POST') {
    
    $date = get_date();
    
        // insert
        if (get_post_data('sql_kind') === 'insert') {
            
            $err_msg = validation(get_post_data('new_name'), get_post_data('new_price'), get_post_data('new_stock'), get_post_data('new_status'), $_FILES["new_img"]["tmp_name"]);

            // 更新系の処理を行う前にトランザクション開始(オートコミットをオフ）
            mysqli_autocommit($link, false);

            if (empty($err_msg)) {
                
                $extension = get_extension($_FILES["new_img"]["tmp_name"]);
                
                $last_id = drink_info_insert($link, get_post_data('new_name'), get_post_data('new_price'), $date, get_post_data('new_status'), $extension);
                $err_msg = stock_insert($link, $last_id, get_post_data('new_stock'), $date, $_FILES["new_img"]["tmp_name"], $extension);
                    
            }
                
            // トランザクション成否判定
            if (count($err_msg) === 0) {
                // 処理確定
                mysqli_commit($link);
                $msg[] = '追加完了！';
            } else {
                // 処理取消
                mysqli_rollback($link);
                }
            }    
        }
        
        // 在庫数操作
        if (get_post_data('sql_kind') === 'update') {
            
            $err_msg = update_validation($link, get_post_data('drink_id'), get_post_data('update_stock'), $date);
            if (empty($err_msg)) {
                $msg[] = '在庫数変更完了';
            }
        }    
            
        // ステータス操作
        if (get_post_data('sql_kind') === 'change') {
            
            $err_msg = change_validation($link, get_post_data('drink_id'), get_post_data('change_status'));
            if (empty($err_msg)) {
                $msg[] = 'ステータス変更完了';
            }
        }
        
    /**
     * 商品情報を取得
     */
    $vending_info = get_db($link);
    if (empty($vending_info)) {
        $err_msg[] = '読み込み失敗';
    }
    close_db_connect($link);
    
// var_dump($err_msg);
if (!empty($err_msg)) {
    foreach ($err_msg as $print) {
        print($print .'<br />'); // 通常のコメント
    }
}
if (!empty($msg)) {
    foreach ($msg as $print) {
        print($print .'<br />'); // 通常のコメント
    }
}

// ファイル読み込み
include_once '../../include/view/managing.php';