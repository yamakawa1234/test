<?php

// 自身のユーザーアカウントを操作する処理

/**
* アカウント情報取得
* @parm obj $link DBハンドル
* @parm str $user_id ユーザーid
* @return str $data ユーザー情報
*/
function get_account_info($link, $user_id) {
    
    $data = array();
    
    // user_idからユーザ名を取得するSQL
    $sql  = 'SELECT * FROM twitter_user_info ';
    $sql .= 'WHERE user_id=\''  .$user_id .'\'';
    
    // クエリ実行
    $data = get_as_array($link, $sql);
    
    return $data;
}

/**
* 新規アカウントを追加
* @return obj $link DBハンドル
* @return str $account アカウント
*/
function insert_account($link, $account, $mail, $nickName, $password) {
    
    $err_msg = array();
    $data = array();
    // 現在時刻を取得
    $date = get_date();
    // 挿入情報をまとめる
    $data = array(
    'user_name'         => $account,
    'user_mail'         => $mail,
    'user_password'     => $password,
    'user_nickname'     => $nickName,
    'date'              => $date,
    );
    
    $sql  = 'INSERT INTO `twitter_user_info`(`user_name`, `user_mail`, `user_password`, `user_nickname`, `date`) ';
    $sql .= 'VALUE (\'' . implode('\',\'', $data) . '\');';
    $result = mysqli_query($link, $sql);
    if ($result === false) {
        $err_msg[] = 'SQL失敗:' . $sql;
    }
    return $err_msg;
}

/**
* メールアドレスとパスワードからuser_idを取得する
* @parm obj $link DBハンドル
* @parm str $account アカウント名
* @parm str $passwd パスワード
* @return str $data ユーザー情報
*/
function get_user_id($link, $account, $passwd) {
    
    $data = array();
    
    $sql = 'SELECT * FROM twitter_user_info 
            WHERE (user_name =\'' . $account . '\' OR user_mail =\'' . $account .'\') AND user_password =\'' . $passwd . '\'';
    
    // クエリ実行
    $data = get_as_array($link, $sql);
    
    return $data;
}

/**
* DB重複チェック
* @param str $str トリム後検査用文字列 
* @param str $colum 検査する対象のカラム名
* @param str $item チェック項目名（エラー用に使用）
* @return str $err_msg エラーメッセージ
*/
function check_double($link, $str, $colum, $item) {
    
    $err_msg = null;
    
    $sql  = 'SELECT ' .$colum .' ';
    $sql .= 'FROM `twitter_user_info` ';
    $sql .= 'WHERE ' .$colum .'=\'' .$str .'\';';
    $result = mysqli_query($link, $sql);
    if ($result === false) {
        $err_msg = 'SQLの実行に失敗しました';
        return $err_msg;
    }
    if (mysqli_fetch_assoc($result) != false) {
        $err_msg = 'この' .$item .'はすでに使用されています:' . $str;
    }
    return $err_msg;
}

/**
* アカウント情報変更
* @pram obj $link DBハンドル
* @pram str $user_id ユーザーid
* @pram str $nickname ニックネーム
* @pram str $password パスワード
* @pram str $place 出身地
* @pram str $prof 自己紹介
* @pram str $tmp_name 画像ファイルの一時保存ネーム
* @pram str $open_level デフォルトの公開範囲
* @return str $err_msg エラーメッセージ
*/
function update_info($link, $user_id, $nickname, $password, $place, $prof, $tmp_name, $open_level) {
    
    $err_msg = array();
    
    $sql  = 'UPDATE  `twitter_user_info` ';
    $sql .= 'SET `user_password`=\'' .$password .'\', `user_nickname`=\'' .$nickname .'\', ';
    $sql .= '`place`=\'' .$place .'\', `prof`=\'' .$prof .'\', ';
    $sql .= '`default_open_level`=\'' .$open_level .'\' ';
    $sql .= 'WHERE `user_id`=' .$user_id;
    $result = mysqli_query($link, $sql);
    if ($result === false) {
        $err_msg[] = 'SQL失敗:' . $sql;
    }
    
    // 画像アップロード
    if (!empty($tmp_name)) {
        
        $extension = get_extension($tmp_name);
        
        // 旧画像拡張子を取得
        $sql = 'SELECT extension FROM twitter_user_info WHERE user_id = ' . $user_id;
        // SQL実行し登録データを配列で取得
        $data = get_as_array($link, $sql);
        // ユーザ名を取得できたか確認
        if (!isset($data[0])) {
            $err_msg[] = 'DB接続エラー';
        }
        if (empty($err_msg)) {
            if (!move_uploaded_file($tmp_name, "./pic_twitter_prof/tmp_" .$user_id .'.' .$extension)) {
                $err_msg[] = 'テンポラリファイルをアップロードできません';
                $err_msg[] = '想定外のエラーが発生しました';
            }
        }
        if (empty($err_msg)) {
            // 画像の削除
            if ($data[0]['extension'] != 'DUMMY') {
                if (!unlink("./pic_twitter_prof/" .$user_id .'.' .$data[0]['extension'])) {
                    $err_msg[] = '既存ファイルを削除できません';
                    $err_msg[] = '想定外のエラーが発生しました';
                }
            }
        }
        if (empty($err_msg)) {
            // 画像のアップロード
            if (!rename("./pic_twitter_prof/tmp_" .$user_id .'.' .$extension, "./pic_twitter_prof/" .$user_id .'.' .$extension)) {
                $err_msg[] = 'ファイルをアップロードできません';
                $err_msg[] = '想定外のエラーが発生しました';
            }
        }   
        if (empty($err_msg)) {
            $sql  = 'UPDATE  `twitter_user_info` ';
            $sql .= 'SET `extension`=\'' .$extension .'\' ';
            $sql .= 'WHERE `user_id`=' .$user_id;
            $result = mysqli_query($link, $sql);
            if ($result === false) {
                $err_msg[] = 'SQL失敗:' . $sql;
            }
        }
    }
    return $err_msg;
}

/**
* ログイン処理
* @parm obj $link 
* @parm str $user_name
* @parm str $passwd 
* @retrn boolean $boolean true:成功 false:失敗
*/
function login($link, $user_name, $passwd) {
    
    // アカウント情報からuser_idを取得
    $data = get_user_id($link, $user_name, $passwd);
    // 登録データを取得できたか確認
    if ((isset($data[0]['user_id'])) AND (is_null($data[0]['withdrawal_flag']))) {
        // セッション変数にuser_idを保存
        $_SESSION['user_id'] = $data[0]['user_id'];
        return true;
    } else {
        return false;
    }
}

/**
* ログアウト処理
* @parm obj $link 
* @parm str $user_name
* @return boolean $boolean true:成功 false:失敗
*/
function logout($link, $session_name) {
    
    // セッション変数を全て削除
    $_SESSION = array();
 
    // ユーザのCookieに保存されているセッションIDを削除
    if (isset($_COOKIE[$session_name])) {
        setcookie($session_name, '', time() - 42000);
        return true;
    }

    return false;
}

/**
* 退会フラグを立てる
* @pram obj $link DBハンドル
* @pram str $user_id 自身のid
* @pram str $receive_id 相手のid
* @pram str $msg メッセージid
* @return array $err_msg エラーメッセージ
*/
function withdrawal_account($link, $user_id) {
    
    $err_msg = array();
        
    $sql  = "UPDATE  `twitter_user_info` 
            SET `withdrawal_flag`='1', `user_mail`=NULL 
            WHERE `user_id`=" . $user_id;
    
    $result = mysqli_query($link, $sql);
    if ($result === false) {
        $err_msg[] = 'SQL失敗:' . $sql;
    }
    return $err_msg;
}
