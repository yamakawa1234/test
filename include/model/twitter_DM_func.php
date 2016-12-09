<?php

// DMに関する機能

/**
* 受信DM一覧を取得
* @parm obj $link DBハンドル
* @parm str $serch_word サーチワード
* @parm str $user_id ユーザーID
* @return array $edit_data クエリより取得した結果(+html上での制御文字を追加したもの)
*/
function get_my_DM_list($link, $user_id) {
    
    $data = array();
    
    // ダイレクトメッセージにてやりとりをしてことのある全ユーザーを取得
    $sql  = "(SELECT sender_id AS list_id FROM twitter_DM 
            WHERE receiver_id = '" . $user_id . "') 
            UNION 
            (SELECT receiver_id AS list_id FROM twitter_DM 
            WHERE sender_id = '" . $user_id . "' AND 
            twitter_DM.delete_flag IS NULL)";
    
    // SQL実行し登録データを配列で取得
    $id_data = get_as_array($link, $sql);
    
    // DMのやりとりをしたことのあるユーザーの一覧を取得
    foreach ($id_data as $key => $value) {
        $sql  = "SELECT twitter_DM.DM_id, twitter_DM.sender_id, twitter_DM.receiver_id, twitter_DM.send_date, twitter_DM.message, 
                sender_info.user_name AS sender_name, 
                sender_info.user_nickname AS sender_nickname, 
                sender_info.extension AS sender_extension, 
                receiver_info.user_name AS receiver_name, 
                receiver_info.user_nickname AS receiver_nickname, 
                receiver_info.extension AS receiver_extension 
                FROM twitter_DM 
                LEFT JOIN `twitter_user_info` AS `sender_info`   ON twitter_DM.sender_id = sender_info.user_id 
                LEFT JOIN `twitter_user_info` AS `receiver_info` ON twitter_DM.receiver_id = receiver_info.user_id 
                WHERE ((twitter_DM.sender_id = '" . $value['list_id'] . "' AND twitter_DM.receiver_id = '" . $user_id ."') OR
                (twitter_DM.sender_id = '" . $user_id . "' AND twitter_DM.receiver_id = '" . $value['list_id'] ."'))
                ORDER BY twitter_DM.send_date DESC 
                LIMIT 1";
        
        // SQL実行し登録データを配列で取得
        $row = get_as_array($link, $sql);
        
        // 順次結合
        $data[$key] = $row;
    }
    // 配列の要素がゼロの場合はエラー回避のため、ソートをスキップ
    if (count($data) != 0 ) {
        // tweet_idでソート
        foreach ($data as $key => $value){
            $key_id[$key] = $value[0]['send_date'];
        }
        array_multisort ( $key_id , SORT_DESC , $data);
    }
    return $data;
}

/**
* 相手とのメッセージ一覧を取得
* @pram obj $link DBハンドル
* @pram str $user_id 自身のid
* @pram str $receiver_id 相手のid
* @return array $data メッセージ一覧
*/
function get_DM($link, $user_id, $receiver_id) {
    
    $data = array();
    
    $sql  = "(SELECT twitter_DM.DM_id, twitter_DM.sender_id, twitter_DM.receiver_id, twitter_DM.send_date, twitter_DM.message, 
            twitter_user_info.user_nickname, twitter_user_info.extension 
            FROM `twitter_DM` 
            LEFT JOIN `twitter_user_info` ON twitter_DM.sender_id = twitter_user_info.user_id 
            WHERE twitter_DM.sender_id = '" . $user_id . "' AND 
            twitter_DM.receiver_id = '" . $receiver_id . "' AND 
            twitter_DM.delete_flag IS NULL 
            ORDER BY twitter_DM.send_date DESC) 
            UNION 
            (SELECT twitter_DM.DM_id, twitter_DM.sender_id, twitter_DM.receiver_id, twitter_DM.send_date, twitter_DM.message, 
            twitter_user_info.user_nickname, twitter_user_info.extension 
            FROM `twitter_DM` 
            LEFT JOIN `twitter_user_info` ON twitter_DM.sender_id = twitter_user_info.user_id 
            WHERE twitter_DM.sender_id = '" . $receiver_id . "' AND 
            twitter_DM.receiver_id = '" . $user_id . "' 
            ORDER BY twitter_DM.send_date DESC) ";
    
    // SQL実行し登録データを配列で取得
    $data = get_as_array($link, $sql);
    
    // 配列の要素がゼロの場合はエラー回避のため、ソートをスキップ
    if (count($data) != 0 ) {
        // tweet_idでソート
        foreach ($data as $key => $value){
            $key_id[$key] = $value['send_date'];
        }
        array_multisort ( $key_id , SORT_ASC , $data);
    }
    return $data;
}

/**
* ダイレクトメッセージを送信する
* @pram obj $link DBハンドル
* @pram str $user_id 自身のid
* @pram str $receive_id 相手のid
* @pram str $msg メッセージ
* @return array $err_msg エラーメッセージ
*/
function send_DM($link, $user_id, $receiver_id, $msg) {
    
    $err_msg = array();
    
    if (block_decision($link, $receiver_id, $user_id) == true) {
    
    $err_msg = DM_validation($msg);
    
    if (empty($err_msg)) {
        // 現在時刻を取得
        $date = get_date();
        
        $data = array(
        'sender_id	'   => $user_id,
        'receiver_id'   => $receiver_id,
        'send_date'     => $date,
        'message'       => $msg,
        );
                
        $sql  = 'INSERT INTO `twitter_DM`(`sender_id`, `receiver_id`, `send_date`, `message`) ';
        $sql .= 'VALUE (\'' . implode('\',\'', $data) . '\');';
        
        $result = mysqli_query($link, $sql);
        if ($result === false) {
            $err_msg[] = 'SQL失敗:' . $sql;
        }
    }
    } else {
        $err_msg[] = 'このユーザーにブロックされています';
        $err_msg[] = 'メッセージを送信することはできません';
    }
    return $err_msg;
}

/**
* ダイレクトメッセージに削除フラグを立てる
* @pram obj $link DBハンドル
* @pram str $user_id 自身のid
* @pram str $receive_id 相手のid
* @pram str $msg メッセージid
* @return array $err_msg エラーメッセージ
*/
function delete_DM($link, $user_id, $receiver_id, $DM_id) {
    
    $err_msg = array();
        
    $sql  = "UPDATE  `twitter_DM` 
            SET `delete_flag`='1' 
            WHERE `DM_id`=" . $DM_id;
    $result = mysqli_query($link, $sql);
    if ($result === false) {
        $err_msg[] = 'SQL失敗:' . $sql;
    }
    return $err_msg;
}