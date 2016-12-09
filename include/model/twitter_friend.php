<?php

// 他のユーザーとの連携機能は、このmodelに記述

/**
* ユーザー検索
* @parm obj $link DBハンドル
* @parm str $serch_word サーチワード
* @parm str $user_id ユーザーID
* @return array $edit_data クエリより取得した結果(+html上での制御文字を追加したもの)
*/
function serch_friend($link, $serch_word, $user_id) {
    
    $data        = array();
    $edit_data   = array();
    $display     = '';
    
    // メールアドレスとパスワードからユーザー情報を取得するSQL
    $sql  = 'SELECT * FROM twitter_user_info ';
    $sql .= 'WHERE (user_nickname  LIKE \'%' . $serch_word . '%\' OR user_mail LIKE \'%' . $serch_word .'%\') AND ';
    $sql .= 'user_id!=' .$user_id .' AND withdrawal_flag IS NULL AND ';
    $sql .= 'default_open_level = 1 ';
    
    // SQL実行し登録データを配列で取得
    $data = get_as_array($link, $sql);
    
    foreach ($data as $key => $value) {
        $sql  = 'SELECT * FROM twitter_follow ';
        $sql .= 'WHERE (user_id=\'' . $user_id . '\' AND followed_id=\'' .$value['user_id'] .'\')';
        
        // SQL実行し登録データを配列で取得
        $follow_info = get_as_array($link, $sql);
        
        if (empty($follow_info)) {
            // ノンフォロー状態
            $display    = 'フォロー';
            $function   = 'follow';
        } else {
            // フォロー状態
            $display    = '解除';
            $function   = 'unfollow';
        }
        
        $follow_info = array(
            'display'   => $display,
            'function'  => $function,
            );
        
        $value      = array_merge($value, $follow_info);
        $edit_data[$key] = $value;
    }
    return $edit_data;
}

/**
* フォロー機能
* @pram obj $link DBハンドル
* @pram str $user_id フォローする側のユーザーid
* @pram str $followed_id フォローされる側のユーザーid
* @return array $err_msg エラーメッセージ
*/
function follow($link, $user_id, $followed_id) {
    
    $err_msg = array();
    
    if (block_decision($link, $user_id, $followed_id) == false) {
        $err_msg[] = 'このユーザーをブロックしているのでフォローできません';
    }
    
    if (block_decision($link, $followed_id, $user_id) == false) {
        $err_msg[] = 'ブロックされているのでこのユーザーをフォローできません';
    }
        
    if (empty($err_msg)) {
        $data = array();
        // 挿入情報をまとめる
        $data = array(
        'user_id'       => $user_id,
        'followed_id'   => $followed_id,
        );
        
        $sql  = 'INSERT INTO `twitter_follow`(`user_id`, `followed_id`) ';
        $sql .= 'VALUE (\'' . implode('\',\'', $data) . '\');';
        
        $result = mysqli_query($link, $sql);
        if ($result === false) {
            $err_msg[] = 'SQL失敗:' . $sql;
        }
    }
    return $err_msg;
}

/**
* フォロー解除
* @pram obj $link DBハンドル
* @pram str $user_id フォローしている側のユーザーid
* @pram str $followed_id フォローされている側のユーザーid
* @return v $err_msg エラーメッセージ
*/
function unfollow($link, $user_id, $followed_id) {
    
    $err_msg = array();
    
    $sql  = 'DELETE FROM `twitter_follow` WHERE user_id=\'' .$user_id .'\'  AND followed_id=\'' .$followed_id .'\'';
    
    $result = mysqli_query($link, $sql);
    if ($result === false) {
        $err_msg[] = 'SQL失敗:' . $sql;
    }
    return $err_msg;
}

/**
* フォローorフォロワーを表示する
* @parm obj $link DBハンドル
* @parm str $control_id 操作者のユーザーid
* @parm str $user_id フォローしている側のユーザーid
* @parm str $display_function 表示するフォローorフォロワー
* @return array $data フレンドリスト
*/
function display_follow_or_follower($link, $control_id, $user_id, $display_function) {
    
    $data      = array();
    $edit_data = array();
    
    // フォロー表示
    $param_join_on = 'twitter_follow.followed_id';
    $param_where   = 'twitter_follow.user_id';
    
    // フォロワー表示
    if ( $display_function === 'follower' ) {
     	$param_join_on = 'twitter_follow.user_id';
     	$param_where = 'twitter_follow.followed_id';
    }
    
    $sql  = "SELECT twitter_user_info.user_name, twitter_user_info.user_nickname, twitter_user_info.place, twitter_user_info.prof, 
            twitter_user_info.extension, twitter_user_info.user_id AS target_id, 
            twitter_follow.user_id, twitter_follow.followed_id 
            FROM `twitter_user_info` 
            LEFT JOIN `twitter_follow` ON twitter_user_info.user_id = " . $param_join_on . 
            " WHERE " . $param_where . " ='" . $user_id . "'";
    
    // SQL実行し登録データを配列で取得
    $data = get_as_array($link, $sql);
    
    foreach ($data as $key => $value) {
        $sql  = 'SELECT * FROM twitter_follow ';
        if ( $display_function === 'follow' ) {
            $sql .= 'WHERE (user_id=\'' . $control_id . '\' AND followed_id=\'' .$value['followed_id'] .'\')';
        } else {
            $sql .= 'WHERE (user_id=\'' . $control_id . '\' AND followed_id=\'' .$value['user_id'] .'\')';
        }
        
        // SQL実行し登録データを配列で取得
        $follow_info = get_as_array($link, $sql);
        
        if (empty($follow_info)) {
            // ノンフォロー状態
            $display    = 'フォロー';
            $function   = 'follow';
        } else {
            // フォロー状態
            $display    = '解除';
            $function   = 'unfollow';
        }
        
        $follow_info = array(
            'display'           => $display,
            'function'          => $function,
            'display_function'  => $display_function,
            );
        
        $value      = array_merge($value, $follow_info);
        $edit_data[$key] = $value;
    }
    
    return $edit_data;
}

/**
* フォローorアンフォロー
* @parm obj $link DBハンドル
* @parm str $user_id フォローする側のユーザーid
* @parm str $followed_id フォローされる側のユーザーid
* @parm str $funciton 機能名
* @return array $err_msg エラーメッセージ
*/
function follow_or_unfollower($link, $user_id, $followed_id, $function) {
    $err_msg = array();
    
    // フォロワー表示
    if ( $function === 'follow' ) {
     	$err_msg = follow($link, $user_id, $followed_id);
    } else {
        $err_msg = unfollow($link, $user_id, $followed_id);
    }
    return $err_msg;
}

/**
* フォローorフォワーを表示(home画面より遷移)
* @parm obj $link DBハンドル
* @parm str $user_id フォローしている側のユーザーid
* @parm str $display_function 表示したいリストのパラメーター:follow or follower
* @return array $data エラーメッセージ
*/
function display_follow_or_follower_mine($link, $user_id, $display_function) {
    
    $data      = array();
    $edit_data = array();
    
    // フォロー表示
    $param_join_on = 'twitter_follow.followed_id';
    $param_where   = 'twitter_follow.user_id';
    
    // フォロワー表示
    if ( $display_function === 'follower' ) {
     	$param_join_on = 'twitter_follow.user_id';
     	$param_where = 'twitter_follow.followed_id';
    }
    
    $sql  = "SELECT twitter_user_info.user_name, twitter_user_info.user_nickname, twitter_user_info.place, twitter_user_info.prof, 
            twitter_user_info.extension, twitter_user_info.user_id AS target_id, 
            twitter_follow.user_id, twitter_follow.followed_id 
            FROM `twitter_user_info` 
            LEFT JOIN `twitter_follow`  ON twitter_user_info.user_id = " . $param_join_on .
            " WHERE " . $param_where . " ='" . $user_id . "'";
    
    // SQL実行し登録データを配列で取得
    $data = get_as_array($link, $sql);
    
    foreach ($data as $key => $value) {
        if ( $display_function === 'follower' ) {
            $sql  = 'SELECT * FROM twitter_follow ';
            $sql .= 'WHERE (user_id=\'' . $user_id . '\' AND followed_id=\'' .$value['user_id'] .'\')';
            
            // SQL実行し登録データを配列で取得
            $follow_info = get_as_array($link, $sql);
            
            if (empty($follow_info)) {
                // ノンフォロー状態
                $display    = 'フォロー';
                $function   = 'follow';
            } else {
                // フォロー状態
                $display    = '解除';
                $function   = 'unfollow';
            }
        } else {
                // フォロー状態
                $display    = '解除';
                $function   = 'unfollow';
        }
        $follow_info = array(
        'display'           => $display,
        'function'          => $function,
        'display_function'  => $display_function,
        );
        
        $value      = array_merge($value, $follow_info);
        $edit_data[$key] = $value;
    }
    return $edit_data;
}

/**
* user nameよりアカウント情報取得
* @parm obj $link DBハンドル
* @parm str $user_name ユーザーid
* @return array $data ユーザー情報
*/
function get_info_by_username($link, $user_name) {
    
    $data = array();
    
    // user_idからユーザ名を取得するSQL
    $sql  = 'SELECT * FROM twitter_user_info ';
    $sql .= 'WHERE user_name=\''  .$user_name .'\'';
    
    // クエリ実行
    $data = get_as_array($link, $sql);
    
    return $data;
}

/**
* 対象ユーザーをフォローしているかチェック
* @parm obj $link DBハンドル
* @parm str $user_id ユーザーID
* @parm str $check_id チェック対象者のID
* @return boolean
*/
function check_follow_status($link, $user_id, $cehck_id) {
    
    $sql  = "SELECT * FROM twitter_follow 
            WHERE (user_id='" . $user_id . "' AND followed_id='" . $cehck_id . "')";
    
    // SQL実行し登録データを配列で取得
    $follow_info = get_as_array($link, $sql);
    
    if (!empty($follow_info)) {
        return true;
    } else {
        return false;
    }
}

/**
* 退会判定
* @parm obj $link DBハンドル
* @parm str $user_id ユーザーID
* @return boolean
*/
function withdrawal_decision($link, $user_id) {
    
    $data = array();
    
    // user_idからユーザ名を取得するSQL
    $sql  = "SELECT * FROM twitter_user_info 
            WHERE user_id='" .$user_id ."' AND withdrawal_flag='1'";
    
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
* 退会時フォロー情報削除
* @pram obj $link DBハンドル
* @pram str $user_id フォローしている側のユーザーid
* @pram str $followed_id フォローされている側のユーザーid
* @return v $err_msg エラーメッセージ
*/
function delete_follow_info($link, $user_id) {
    
    $err_msg = array();
    
    $sql  = 'DELETE FROM `twitter_follow` WHERE user_id=\'' .$user_id .'\'  OR followed_id=\'' .$user_id .'\'';
    
    $result = mysqli_query($link, $sql);
    if ($result === false) {
        $err_msg[] = 'SQL失敗:' . $sql;
    }
    return $err_msg;
}

/**
* おすすめユーザーを表示
* @parm obj $link DBハンドル
* @parm str $user_id ユーザーid
* @return array $edit_data おすすめユーザー一覧
*/
function display_recommended_user($link, $user_id) {
    
    $edit_data = array();
    
    $sql  = "SELECT twitter_follow.user_id, twitter_follow.followed_id, 
            twitter_follow2.user_id AS user_id2, 
            twitter_follow2.followed_id AS recommended_id, 
            twitter_user_info.user_name, twitter_user_info.user_nickname, 
            twitter_user_info.place, twitter_user_info.prof, twitter_user_info.extension 
            FROM twitter_follow 
            LEFT JOIN twitter_follow AS twitter_follow2 ON twitter_follow.followed_id = twitter_follow2.user_id 
            LEFT JOIN twitter_user_info ON twitter_follow2.followed_id = twitter_user_info.user_id 
            WHERE twitter_follow.user_id = " . $user_id . " AND 
            twitter_user_info.default_open_level = 1 AND 
            twitter_follow2.followed_id NOT IN(SELECT followed_id FROM twitter_follow WHERE user_id = " . $user_id . ") AND 
            twitter_follow2.followed_id <> " . $user_id . " AND 
            twitter_follow2.followed_id NOT IN(SELECT blocked_id FROM twitter_block WHERE practitioner_id = " . $user_id . ") AND  
            twitter_follow2.followed_id NOT IN(SELECT practitioner_id FROM twitter_block WHERE blocked_id = " . $user_id . ") 
            LIMIT 0, 3";
    
    // SQL実行し登録データを配列で取得
    $data = get_as_array($link, $sql);
    
    if (empty($data)) {
        
        $sql  = "SELECT 
                twitter_user_info.user_name, twitter_user_info.user_nickname, 
                twitter_user_info.place, twitter_user_info.prof, twitter_user_info.extension, 
                twitter_user_info.user_id AS recommended_id 
                FROM twitter_user_info 
                LEFT JOIN twitter_tweet ON twitter_user_info.user_id = twitter_tweet.user_id 
                WHERE 
                twitter_user_info.default_open_level = 1 AND 
                twitter_user_info.user_id <> " . $user_id . " AND 
                twitter_user_info.user_id NOT IN(SELECT followed_id FROM twitter_follow WHERE user_id = " . $user_id . ") 
                GROUP BY twitter_tweet.user_id 
                ORDER BY count(twitter_tweet.user_id) DESC 
                LIMIT 0, 3";
                
        $data = get_as_array($link, $sql);
                
    }
    
    foreach ($data as $key => $value) {
        // 自身のツイート数取得
        $tweet_count = count(get_my_tweet($link, $value['recommended_id']));
        $key_id[$key] = $tweet_count;
        
        $value = array_merge($value, array('tweet_count' => $tweet_count));
        $edit_data[$key] = $value;
    }
    
    // おすすめユーザーがゼロ件の場合ソートをスキップ
    if (count($edit_data) != 0 ) {
        array_multisort ( $key_id , SORT_DESC , $edit_data);
    }
    return $edit_data;
}

/**
* デフォルト公開レベルを取得
* @parm obj $link DBハンドル
* @parm str $user_id ユーザーID
* @parm str $check_id チェック対象者のID
* @return boolean
*/
function get_default_open_level($link, $user_id, $cehck_id) {
    
    $get_info = array();
    
    $sql  = "SELECT * FROM twitter_user_info 
            WHERE (user_id = '" . $cehck_id . "' AND default_open_level = '2')";
            
    // SQL実行し登録データを配列で取得
    $get_info = get_as_array($link, $sql);
    
    if (!empty($get_info)) {
        return true;
    } else {
        return false;
    }
}

/**
* 相互フォローしているときのみtweetを参照することができる
* @parm obj $link DBハンドル
* @parm str $user_id ユーザーID
* @parm str $check_id チェック対象者のID
* @return boolean
*/
function check_default_open_level($link, $user_id, $cehck_id) {
    
    $check_info = array();
    
    $sql  = "SELECT * FROM twitter_follow 
            WHERE (user_id = '" . $user_id . "' AND followed_id = '" . $cehck_id . "')";
                
    // SQL実行し登録データを配列で取得
    $check_info = get_as_array($link, $sql);
    
    if (!empty($check_info)) {
        print "true";
        return true;
    } else {
        print "false";
        return false;
    }
}
