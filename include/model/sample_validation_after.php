<?php
/**
* 新規アカウント登録時_バリデーション(trim必須)
* @return obj $link DBハンドル
* @param str $account アカウント名
* @param str $mail メールアドレス
* @param str $nickName ニックネーム
* @param str $password パスワード
* @return str $err_msg エラーメッセージ
*/
function registration_validation($link, $account, $mail, $nickName, $password) {
    
    $err_msg = array();
    
    // アカウント名
    $msg = check_length($account, 15, 'アカウント名');
    if (!empty($msg)) {
        $err_msg[] = $msg;
    }
    
    // メールアドレス
    $regexp = '|^[0-9a-z_./?-]+@([0-9a-z-]+\.)+[0-9a-z-]+$|';
    $msg    = '形式が異なります。メールアドレスをもう一度ご確認ください';
    
    $msg = check_regexp($mail, 'メールアドレス', $regexp, $msg);
    if (!empty($msg)) {
        $err_msg[] = $msg;
    }

    if (empty($err_msg)) {
        $msg = check_double($link, $account, 'user_name', 'アカウント名');
        if (!empty($msg)) {
            $err_msg[] = $msg;
        }
        $msg = check_double($link, $mail, 'user_mail', 'メールアドレス');
        if (!empty($msg)) {
            $err_msg[] = $msg;
        }
    }

    $tmp = change_validation($link, $nickName, $password);
    $err_msg = array_merge($err_msg, $tmp);
    
    return $err_msg;
}

/**
* アカウント情報変更時_バリデーション(trim必須)
* @return obj $link DBハンドル
* @param str $nickName ニックネーム
* @param str $password パスワード
* @return str $err_msg エラーメッセージ
*/
function change_validation($link, $nickName, $password) {
    
    $err_msg = array();
    
    //ニックネーム
    $msg = check_length($nickName, 15, 'ニックネーム');
    if (!empty($msg)) {
        $err_msg[] = $msg;
    }
    
    // パスワード
    $regexp = '/\A(?=.*?[a-z])(?=.*?[A-Z])(?=.*?\d)[a-zA-Z\d]{8,16}+\z/';
    $msg    = 'パスワードは半角英小文字大文字数字をそれぞれ1種類以上含む8文字以上16文字以下で入力してください';
    
    $msg = check_regexp($password, 'パスワード', $regexp, $msg);
    if (!empty($msg)) {
        $err_msg[] = $msg;
    }
    
    return $err_msg;
}
