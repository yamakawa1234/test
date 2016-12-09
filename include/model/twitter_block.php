<?php

// ブロック処理機能

/**
* ブロック処理
* @parm obj $link DBハンドル
* @parm str $user_id ユーザーID
* @parm str $blocked_id ブロックされるID
* @return array $err_msg エラーメッセージ
*/
function user_block($link, $user_id, $blocked_id) {
    
    $err_msg = array();
    
    $data = array();
    // 挿入情報をまとめる
    $data = array(
    'practitioner_id'   => $user_id,
    'blocked_id'        => $blocked_id,
    );
    
    $sql  = 'INSERT INTO `twitter_block`(`practitioner_id`, `blocked_id`) ';
    $sql .= 'VALUE (\'' . implode('\',\'', $data) . '\');';
    
    $result = mysqli_query($link, $sql);
    if ($result === false) {
        $err_msg[] = 'SQL失敗:' . $sql;
    }
    
    // エラーがない場合、後続処理を行う
    if (empty($err_msg)) {
        // 相互アンフォロー
        $wk_msg1 = unfollow($link, $user_id, $blocked_id);
        $wk_msg2 = unfollow($link, $blocked_id, $user_id);
        // リツイートテーブル削除
        $wk_msg3 = delete_retweet_by_user_id($link, $user_id, $blocked_id);
        $wk_msg4 = delete_retweet_by_user_id($link, $blocked_id, $user_id);
        // リプライテーブル削除
        $wk_msg5 = delete_reply_by_user_id($link, $user_id, $blocked_id);
        $wk_msg6 = delete_reply_by_user_id($link, $blocked_id, $user_id);
        
        // エラーメッセージを結合
        $err_msg  = $wk_msg1 + $wk_msg2 + $wk_msg3 + $wk_msg4 + $wk_msg5 + $wk_msg6;
    }
    return $err_msg;
}

/**
* ブロック判定
* @parm obj $link DBハンドル
* @parm str $user_id ユーザーID
* @parm str $blocked_id ブロック判定相手のID
* @return boolean 
*/
function block_decision($link, $user_id, $decision_id) {
    
    $data = array();
    
    // user_idからユーザ名を取得するSQL
    $sql  = "SELECT * FROM twitter_block 
            WHERE practitioner_id='" .$user_id ."' AND blocked_id='" .$decision_id ."'";
    
    // クエリ実行
    $data = get_as_array($link, $sql);
    
    // ブロック判定
    if (empty($data)) {
        return true;
    } else {
        return false;
    }
}

/**
* ブロック解除処理
* @parm obj $link DBハンドル
* @parm str $user_id ユーザーID
* @parm str $blocked_id ブロック解除されるID(現在ブロック中)
* @return array $err_msg エラーメッセージ
*/
function user_unblock($link, $user_id, $blocked_id) {
    
    $err_msg = array();
    
    // SQL
    $sql  = "DELETE FROM twitter_block 
            WHERE practitioner_id=" . $user_id . " AND blocked_id=" . $blocked_id;
    
    // クエリ実行
    $result = mysqli_query($link, $sql);
    if ($result === false) {
        $err_msg[] = 'SQL失敗:' . $sql;
    }
    return $err_msg;
}
