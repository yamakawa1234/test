<?php

// tweet操作に関するmodel

/**
* tweet
* @return obj $link DBハンドル
* @return str $user_id ユーザーid
* @return str $tweet ツイート
* @return str $err_msg エラーメッセージ
*/
function insert_tweet($link, $user_id, $tweet) {
    
    $err_msg = array();
    $date = get_date();
    
    $data = array();
    // 挿入情報をまとめる
    $data = array(
    'user_id'       => $user_id,
    'tweet'         => $tweet,
    'tweet_time'    => $date,
    );
    
    $sql  = 'INSERT INTO `twitter_tweet`(`user_id`, `tweet`, `tweet_time`) ';
    $sql .= 'VALUE (\'' . implode('\',\'', $data) . '\');';
    $result = mysqli_query($link, $sql);
    if ($result === false) {
        $err_msg[] = 'SQL失敗:' . $sql;
    }
    return $err_msg;
}

/**
* homeページに表示するtweet取得
* @return obj $link DBハンドル
* @return str $user_id ユーザーid
* @return str $data ツイート配列
*/
function get_tweet($link, $user_id) {
    
    $data          = array();
    $data_me       = array();
    $data_follow   = array();
    $data_reply    = array();
    
    // 自身のtweet
    $data_me = get_my_tweet($link, $user_id);
    
    // フォロワーのtweet
    $sql  = 'SELECT twitter_tweet.tweet_id, twitter_tweet.user_id, twitter_tweet.tweet, twitter_tweet.tweet_time, ';
    $sql .= 'twitter_user_info.user_name, twitter_user_info.user_nickname, twitter_user_info.extension  ';
    $sql .= 'FROM `twitter_tweet` ';
    $sql .= 'LEFT JOIN `twitter_user_info` ON twitter_tweet.user_id = twitter_user_info.user_id ';
    $sql .= 'LEFT JOIN `twitter_follow` ON twitter_tweet.user_id = twitter_follow.followed_id ';
    $sql .= 'LEFT JOIN `twitter_reply` ON twitter_tweet.tweet_id = twitter_reply.tweet_id ';
    $sql .= 'WHERE ((twitter_follow.user_id=\'' . $user_id . '\') AND (twitter_user_info.withdrawal_flag IS NULL) ';
    $sql .= 'AND (twitter_tweet.delete_flag IS NULL) AND (twitter_reply.receiver<>\'' . $user_id  . '\')) ';
    $sql .= 'ORDER BY twitter_tweet.tweet_id DESC';
    
    // クエリ実行
    $data_follow = get_as_array($link, $sql);
    // リプライ取得
    $data_reply = get_send_me($link, $user_id);

    $data = array_merge($data_me, $data_follow, $data_reply);
    
    // tweetの場合はエラー回避のため、ソートをスキップ
    if (count($data) != 0 ) {
        // tweet_idでソート
        foreach ($data as $key => $value){
            $key_id[$key] = $value['tweet_time'];
        }
        array_multisort ( $key_id , SORT_DESC , $data);
    }
    return $data;
}

/**
* 自身のtweet取得
* @parm obj $link DBハンドル
* @parm str $user_id ユーザーid
* @return str $data ツイート配列
*/
function get_my_tweet($link, $user_id) {
    
    $data           = array();
    $data_user      = array();
    $data_reply     = array();
    $data_retweet   = array();
    
    // SQL
    $sql  = 'SELECT twitter_tweet.tweet_id, twitter_tweet.user_id, twitter_tweet.tweet, twitter_tweet.tweet_time, ';
    $sql .= 'twitter_user_info.user_name, twitter_user_info.user_nickname, twitter_user_info.extension ';
    $sql .= 'FROM `twitter_tweet` ';
    $sql .= 'LEFT JOIN `twitter_user_info` ON twitter_tweet.user_id = twitter_user_info.user_id ';
    $sql .= 'WHERE ((twitter_tweet.user_id=\'' .$user_id .'\') AND (twitter_tweet.delete_flag IS NULL)) ';
    $sql .= 'ORDER BY twitter_tweet.tweet_id DESC';
    
    // クエリ実行
    $data_user = get_as_array($link, $sql);
    
    // リツイート取得
    $data_retweet = get_my_retweet($link, $user_id);

    $data = array_merge($data_user, $data_reply, $data_retweet);
    
    // tweetの場合はエラー回避のため、ソートをスキップ
    if (count($data) != 0 ) {
        // tweet_idでソート
        foreach ($data as $key => $value){
            $key_id[$key] = $value['tweet_time'];
        }
        array_multisort ( $key_id , SORT_DESC , $data);
    }
    return $data;
}

/**
* tweet削除
* @parm obj $link DBハンドル
* @parm str $tweet_id 削除するツイートid
* @parm str $user_id ユーザーid
* @return str $err_msg エラーメッセージ
*/
function delete_tweet($link, $tweet_id, $user_id) {
    
    $err_msg = array();
    
    // SQL
    /*
    $sql  = 'DELETE FROM `twitter_tweet` '; 
    $sql .= 'WHERE tweet_id=' .$tweet_id .' AND user_id=' .$user_id;
    */
    
    $sql  = "UPDATE twitter_tweet 
            SET delete_flag='1' 
            WHERE tweet_id=" . $tweet_id ;
    
    // クエリ実行
    $result = mysqli_query($link, $sql);
    if ($result === false) {
        $err_msg[] = 'SQL失敗:' . $sql;
    }
    return $err_msg;
}

/**
* 自身宛の返信のtweetを取得
* @parm obj $link DBハンドル
* @parm str $user_id ユーザーid
* @return str $data クエリ結果
*/
function get_send_me($link, $user_id) {
    
    $err_msg = array();
    
    $sql  = 'SELECT twitter_tweet.tweet_id, twitter_tweet.user_id, twitter_tweet.tweet, twitter_tweet.tweet_time, ';
    $sql .= 'twitter_user_info.user_name, twitter_user_info.user_nickname,twitter_user_info.extension ';
    $sql .= 'FROM `twitter_tweet` ';
    $sql .= 'LEFT JOIN `twitter_user_info` ON twitter_tweet.user_id = twitter_user_info.user_id ';
    $sql .= 'LEFT JOIN `twitter_reply` ON twitter_tweet.tweet_id = twitter_reply.tweet_id ';
    $sql .= 'WHERE ((twitter_reply.receiver=\'' .$user_id .'\') AND (twitter_tweet.delete_flag IS NULL)) ';
    $sql .= 'ORDER BY twitter_tweet.tweet_id DESC';
    
    $data = get_as_array($link, $sql);
    return $data;
}

/**
* リツイート
* @parm obj $link DBハンドル
* @parm str $user_id ユーザーid
* @parm str $user_id ツイートid
* @return str $err_msg エラーメッセージ
*/
function insert_retweet($link, $user_id, $tweet_id) {
    
    $err_msg = array();
    
    $sql  = 'SELECT * ';
    $sql .= 'FROM `twitter_tweet` ';
    $sql .= 'WHERE (tweet_id=\'' .$tweet_id .'\') ';
    $sql .= 'ORDER BY tweet_id DESC';
    
    // クエリ実行
    $data = get_as_array($link, $sql);
    
    if (!empty($data)) {
        // $tweet_id = mysqli_insert_id($link);
        $insert_data = array();
        $date = get_date();
        // 挿入情報をまとめる
        $insert_data = array(
        'retweet_user_id'       => $user_id,
        'retweeted_user_id'     => $data[0]['user_id'],
        'retweeted_tweet_id'    => $data[0]['tweet_id'],
        'date'                  => $date,
        );
        
        $sql  = 'INSERT INTO `twitter_retweet`(`retweet_user_id`, `retweeted_user_id`, `retweeted_tweet_id`, `date`) ';
        $sql .= 'VALUE (\'' . implode('\',\'', $insert_data) . '\');';
        $result = mysqli_query($link, $sql);
        if ($result === false) {
            $err_msg[] = 'SQL失敗:' . $sql;
        }
    }
    return $err_msg;
}

/**
* 自身が行ったretweetを取得
* @parm obj $link DBハンドル
* @parm str $user_id ユーザーid
* @return str $data クエリ結果
*/
function get_my_retweet($link, $user_id) {
    
    $err_msg = array();
    
    $sql  = 'SELECT twitter_tweet.tweet_id, twitter_tweet.user_id, twitter_tweet.tweet, ';
    $sql .= 'twitter_user_info.user_name, twitter_user_info.user_nickname, twitter_user_info.extension, ';
    $sql .= 'twitter_retweet.retweet_id, twitter_retweet.date AS tweet_time, twitter_user_info2.user_id AS retweet_user_id, twitter_user_info2.user_nickname AS retweet_user_nickname ';
    $sql .= 'FROM `twitter_tweet` ';
    $sql .= 'LEFT JOIN `twitter_user_info` ON twitter_tweet.user_id = twitter_user_info.user_id ';
    $sql .= 'LEFT JOIN `twitter_retweet` ON twitter_tweet.tweet_id = twitter_retweet.retweeted_tweet_id ';
    $sql .= 'LEFT JOIN `twitter_user_info` AS `twitter_user_info2` ON twitter_retweet.retweet_user_id = twitter_user_info2.user_id ';
    $sql .= 'WHERE ((twitter_retweet.retweet_user_id=\'' .$user_id .'\') AND (twitter_tweet.delete_flag IS NULL)) ';
    $sql .= 'ORDER BY twitter_retweet.retweet_id DESC';
    
    $data = get_as_array($link, $sql);
    return $data;
}

/**
* ユニークキーよりretweetの取り消し
* @parm obj $link DBハンドル
* @parm str $retweet_id 削除するリツイートid
* @return str $err_msg エラーメッセージ
*/
function delete_retweet($link, $retweet_id) {
    
    $err_msg = array();
    
    // SQL
    $sql  = 'DELETE FROM `twitter_retweet`'; 
    $sql .= 'WHERE retweet_id=' .$retweet_id;
    
    // クエリ実行
    $result = mysqli_query($link, $sql);
    if ($result === false) {
        $err_msg[] = 'SQL失敗:' . $sql;
    }
    return $err_msg;
}

/**
* ユーザーIDよりretweetの取り消し
* @parm obj $link DBハンドル
* @parm str $retweet_id 削除するリツイートid
* @return str $err_msg エラーメッセージ
*/
function delete_retweet_by_user_id($link, $retweet_id, $retweeted_id) {
    
    $err_msg = array();
    
    // SQL
    $sql  = "DELETE FROM twitter_retweet 
            WHERE retweet_user_id=" . $retweet_id . " AND retweeted_user_id=" . $retweeted_id;
    
    // クエリ実行
    $result = mysqli_query($link, $sql);
    if ($result === false) {
        $err_msg[] = 'SQL失敗:' . $sql;
    }
    return $err_msg;
}

/**
* 返信ツイート
* @parm str $link  DBハンドル
* @parm str $tweet ツイート内容
* @parm str $user_id ユーザーid
* @return array $err_msg エラーメッセージ
*/
function tweet_receive($link, $tweet, $user_id, $tweet_id) {
    
    $err_msg = array();
    
    // @(アットマーク)チェック
    preg_match_all("/@[^\s　]+/", $tweet, $out, PREG_PATTERN_ORDER);
    if (!empty($out[0])) {
        foreach ($out[0] as $key => $value) {
            if ($key === 0) {
                $sql  = 'SELECT * FROM twitter_user_info ';
                $sql .= 'WHERE user_name=\'' .mb_substr($value, 1) .'\'  ';
            } else {
                $sql .= 'OR user_name=\'' .mb_substr($value, 1) .'\'';
            }
        }
        // SQL実行し登録データを配列で取得
        $user_data = get_as_array($link, $sql);
    }
    
    if (!empty($user_data)) {
        foreach ($user_data as $key => $value) {
            $data = array(
            'sender	'   => $user_id,
            'receiver'  => $value['user_id'],
            'tweet_id'  => $tweet_id,
            );
            
            $sql  = 'INSERT INTO `twitter_reply`(`sender`, `receiver`, `tweet_id`) ';
            $sql .= 'VALUE (\'' . implode('\',\'', $data) . '\');';
            
            $result = mysqli_query($link, $sql);
            if ($result === false) {
                $err_msg[] = 'SQL失敗:' . $sql;
            }
        }
    }    
    return $err_msg;
}

/**
* ユーザーIDより返信の取り消し
* @parm obj $link DBハンドル
* @parm str $send_id 送信者のユーザーID
* @parm str $receiver_id 受信者のユーザーID
* @return str $err_msg エラーメッセージ
*/
function delete_reply_by_user_id($link, $sender_id, $receiver_id) {
    
    $err_msg = array();
    
    // SQL
    $sql  = "DELETE FROM twitter_reply 
            WHERE sender=" . $sender_id . " AND receiver=" . $receiver_id;
    
    // クエリ実行
    $result = mysqli_query($link, $sql);
    if ($result === false) {
        $err_msg[] = 'SQL失敗:' . $sql;
    }
    return $err_msg;
}

/**
* ハッシュタグ登録
* @parm str $link DBハンドル
* @parm str $tweet ツイート内容
* @parm str $tweet_id ツイートid
* @return array $err_msg エラーメッセージ
*/
function registration_hash_tag($link, $tweet, $tweet_id) {
    
    $err_msg = array();
    $date = get_date();
    
    // @(アットマーク)チェック
    preg_match_all("/#[\S]+/", $tweet, $out, PREG_PATTERN_ORDER);
    if (!empty($out[0])) {
        foreach ($out[0] as $key => $value) {
            $sql  = "SELECT hash_tag_id, word, registration_date, update_date 
                    FROM twitter_hash_tag 
                    WHERE word='" . $value . "'";
            
            // SQL実行し登録データを配列で取得
            $hash_tag_data = get_as_array($link, $sql);
            
            if (empty($hash_tag_data)) {
                $data = array(
                'word'              => $value,
                'registration_date' => $date,
                'update_date'       => $date,
                );
                
                $sql  = 'INSERT INTO twitter_hash_tag(word, registration_date, update_date) ';
                $sql .= 'VALUE (\'' . implode('\',\'', $data) . '\');';
                
                $result = mysqli_query($link, $sql);
                if ($result === false) {
                    $err_msg[] = 'SQL失敗:' . $sql;
                } else {
                    $hash_tag_id = mysqli_insert_id($link);
                    
                    $data = array(
                    'hash_tag_id'   => $hash_tag_id,
                    'tweet_id'      => $tweet_id,
                    'date'          => $date,
                    );
                    
                    $sql  = 'INSERT INTO twitter_hash_tag_relation(hash_tag_id, tweet_id, date) ';
                    $sql .= 'VALUE (\'' . implode('\',\'', $data) . '\');';
                    
                    $result = mysqli_query($link, $sql);
                    if ($result === false) {
                        $err_msg[] = 'SQL失敗:' . $sql;
                    }
                }
            } else {
                $sql  = "UPDATE twitter_hash_tag 
                        SET update_date='" . $date . "' 
                        WHERE word='" . $hash_tag_data[0]['word'] . "'";
                
                $result = mysqli_query($link, $sql);
                if ($result === false) {
                    $err_msg[] = 'SQL失敗:' . $sql;
                } else {
                    
                    $data = array(
                    'hash_tag_id'   => $hash_tag_data[0]['hash_tag_id'],
                    'tweet_id'      => $tweet_id,
                    'date'          => $date,
                    );
                    
                    $sql  = 'INSERT INTO twitter_hash_tag_relation(hash_tag_id, tweet_id, date) ';
                    $sql .= 'VALUE (\'' . implode('\',\'', $data) . '\');';
                    
                    $result = mysqli_query($link, $sql);
                    if ($result === false) {
                        $err_msg[] = 'SQL失敗:' . $sql;
                    }
                }
            }
        }
    }
    return $err_msg;
}

/**
* トレンド取得
* @parm str $link DBハンドル
* @parm str $term 開始日時
* @return array $data ハッシュタグデータ
*/
function get_trend($link, $trem) {
    
    $data = array();
    $date = get_date();
    
    $start_date = date('Y年m月d日 H:i:s', strtotime("-" . $trem . "day"));
    
    $sql  = "SELECT twitter_hash_tag.word, twitter_hash_tag_relation.hash_tag_id, count(twitter_hash_tag_relation.hash_tag_id) 
            FROM twitter_hash_tag 
            LEFT JOIN `twitter_hash_tag_relation` ON twitter_hash_tag.hash_tag_id = twitter_hash_tag_relation.hash_tag_id 
            WHERE twitter_hash_tag_relation.date >= '" . $start_date . "' 
            GROUP BY twitter_hash_tag.word 
            ORDER BY count(twitter_hash_tag_relation.hash_tag_id) DESC
            LIMIT 0, 10";
            
    // SQL実行し登録データを配列で取得
    $data = get_as_array($link, $sql);
    
    return $data;
}

/**
* ハッシュタグのツイートを表示
* @parm obj $link DBハンドル
* @parm str $user_id ユーザーID
* @parm str $hash_tag_id ハッシュタグワード
* @return array $data ハッシュタグを含んだツイート一覧
*/
function display_hash_tag($link, $user_id, $word) {
    
    $data = array();
    
    $sql  = "SELECT twitter_hash_tag.word, twitter_hash_tag_relation.hash_tag_id, 
            twitter_tweet.tweet_id, twitter_tweet.user_id, twitter_tweet.tweet, twitter_tweet.tweet_time, 
            twitter_user_info.user_name, twitter_user_info.user_nickname, twitter_user_info.extension 
            FROM twitter_hash_tag 
            LEFT JOIN `twitter_hash_tag_relation` ON twitter_hash_tag.hash_tag_id = twitter_hash_tag_relation.hash_tag_id
            LEFT JOIN `twitter_tweet` ON twitter_hash_tag_relation.tweet_id = twitter_tweet.tweet_id 
            LEFT JOIN `twitter_user_info` ON twitter_tweet.user_id = twitter_user_info.user_id 
            WHERE twitter_hash_tag.word = '" . $word . "' AND 
            twitter_user_info.withdrawal_flag IS NULL AND 
            twitter_tweet.user_id NOT IN(SELECT blocked_id FROM twitter_block WHERE practitioner_id = " . $user_id . ") AND  
            twitter_tweet.user_id NOT IN(SELECT practitioner_id FROM twitter_block WHERE blocked_id = " . $user_id . ") 
            ORDER BY twitter_tweet.tweet_id DESC";
    
    // SQL実行し登録データを配列で取得
    $data = get_as_array($link, $sql);
    
    return $data;
}


/**
* 返信ボタンを押した時
* @parm obj $link DBハンドル
* @parm str $receiver_name 受信先のユーザーネーム
* @return str $edit_name @付与後の名前
*/
function reply_name($link, $receiver_name) {
    
    $edit_name = '@' .$receiver_name .' ';
    
    return $edit_name;
}
