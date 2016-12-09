<?php

// 設定ファイル読み込み
require_once '../../include/conf/const.php';
// 関数ファイル読み込み
require_once '../../include/model/function.php';
 
// 変数初期化
$msg        = array(); // 通常メッセージ用配列
$err_msg    = array(); // エラーメッセージ用配列
 
// DB接続
$link = get_db_connect();

// リクエストメソッド取得
$request_method = get_request_method();
 
if ($request_method === 'POST') {
 
    // POST値取得
    //$name       = get_post_data('name');
    //$comment    = get_post_data('comment');
    
    // 名前、発言の入力規則チェック
    $err_msg = validation(get_post_data('name'), get_post_data('comment'));
    
    if (insert_msg($link, get_post_data('name'), get_post_data('comment'), $err_msg) == true) {
        $msg        = '発言成功';
    } else {
        $msg        = '予期せぬエラーが発生しました';
    }
    // 宿題！！！
    // インプット　エラーメッセージ
    // アウトプット boolean
    // falseが返ってきたら、メッセージ出力
    // *ここから分離可能--------------
    /*if (empty($err_msg)) {
        
        // 現在時刻を取得
        $date = date('Y-m-d H:i:s');
        
        $boolean = db_insert($link, $name, $date, $comment);
        if ($boolean = true) {
            $msg        = '発言成功';
        } else {
            $err_msg    = '発言失敗';
        }
        
    }*/
    // *ここまで分離可能--------------
    
}    

// 商品の一覧を取得
$bbs_data = db_select($link);
 
// DB切断
close_db_connect($link);

// 新規追加テンプレートファイル読み込み
include_once '../../include/view/bbs.php';
