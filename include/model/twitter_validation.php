<?php

// 文字列操作は、このmodelに記述

/**
* get_date
* @return str $date 現在時刻
*/
function get_date() {
    // 現在時刻を取得
    $date = date('Y年m月d日 H:i:s');
    return $date;
}

/**
* 文字列トリム
* @parm str $str トリム前文字列
* @return str トリム後文字列
*/
function space_trim($str) {
    // 行頭の半角、全角スペースを、空文字に置き換える
    $str = preg_replace('/^[ 　]+/u', '', $str);
 
    // 末尾の半角、全角スペースを、空文字に置き換える
    $str = preg_replace('/[ 　]+$/u', '', $str);
 
    return $str;
}

/**
* トリム&HTMLエンティティ
* @parm str  $str 変換前文字
* @return str 変換後文字
*/
function trim_and_entity($str) {
    $str = space_trim($str);
    $str = htmlspecialchars($str, ENT_QUOTES, HTML_CHARACTER_SET);
    return $str;
}

/**
* 特殊文字をHTMLエンティティに変換する(2次元配列の値)
* @parm array  $assoc_array 変換前配列
* @return array 変換後配列
*/
function entity_assoc_array2($assoc_array) {
 
    foreach ($assoc_array as $key => $value) {
        // 特殊文字をHTMLエンティティに変換
        $assoc_array[$key] = trim_and_entity($assoc_array[$key]);
        
    }
    return $assoc_array;
}

/**
* 正規表現入力チェック
* @parm str $str 正規表現チェック前項目
* @parm str $item チェック項目名
* @parm str $regexp 正規表現条件
* @parm str $msg エラー時のメッセージ 
* @return array str $err_msg エラーメッセージ
*/
function check_regexp($str, $item, $regexp, $msg) {
    
    $err_msg = array();
    
    if (!empty($str)) {
        if (preg_match($regexp, $str, $macthes) != 1) {
            $err_msg = $item .';' .$msg;
        }
    } else {
        $err_msg = $item .':未入力です';
    }
    return $err_msg;
}

/**
* 新規アカウント登録時_バリデーション(trim必須)
* @parm obj $link DBハンドル
* @parm str $account アカウント名
* @parm str $mail メールアドレス
* @parm str $nickName ニックネーム
* @parm str $password パスワード
* @return str $err_msg エラーメッセージ
*/
function registration_validation($link, $account, $mail, $nickName, $password) {
    
    $err_msg = array();
    
    // アカウント名
    $regexp = '/@[0-9A-Za-z]+/';
    $msg    = 'アカウント名は15文字以内の半角英小文字大文字または数字の組み合わせのみ使用できます';
    
    $msg = check_regexp($mail, 'アカウント名', $regexp, $msg);
    if (!empty($msg)) {
        $err_msg[] = $msg;
    }
    
    /*
    // アカウント名
    $msg = check_length2($account, 15, 'アカウント名', false);
    if (!empty($msg)) {
        $err_msg[] = $msg;
    }
    */
    
    // メールアドレス
    $regexp = '|^[0-9a-z_./?-]+@([0-9a-z-]+\.)+[0-9a-z-]+$|';
    $msg    = '形式が異なります。メールアドレスをもう一度ご確認ください';
    
    $msg = check_regexp($mail, 'メールアドレス', $regexp, $msg);
    if (!empty($msg)) {
        $err_msg[] = $msg;
    }
    
    //ニックネーム
    $msg = check_length2($nickName, 15, 'ニックネーム', false);
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
    return $err_msg;
}

/**
* ツイート_バリデーション(trim必須)
* @parm str $tweet ツイート内容
* @return str $err_msg エラーメッセージ
*/
function tweet_validation($tweet) {
    
    $err_msg = array();

    // ツイート
    $msg = check_length2($tweet, 140, 'ツイート', false);
    if (!empty($msg)) {
        $err_msg[] = $msg;
    }
    return $err_msg;
}

/**
* アカウント情報変更時_バリデーション(trim必須)
* @parm obj $link DBハンドル
* @parm str $mail メールアドレス
* @parm str $nickName ニックネーム
* @parm str $password パスワード
* @parm str $place 出身地
* @parm str $prof 自己紹介
* @parm str $tmp_name プロフィール画像
* @return str $err_msg エラーメッセージ
*/
function change_validation($link, $nickName, $password, $place, $prof, $tmp_name) {
    
    $err_msg = array();
    
    // ニックネーム
    $msg = check_length2($nickName, 15, 'ニックネーム', false);
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
    
    // 出身地
    $msg = check_length2($place, 30, '出身地', true);
    if (!empty($msg)) {
        $err_msg[] = $msg;
    }
    
    // 自己紹介
    $msg = check_length2($place, 400, '自己紹介', true);
    if (!empty($msg)) {
        $err_msg[] = $msg;
    }
    
    // プロフィール画像
    // ファイルが存在するか？
    if (!empty($tmp_name)) {;
        // 画像情報の取得
        $file_nm = $tmp_name;
        // 学習メモ:"getimagesize"は拡張子を返してくれる
        $imageInfo = getimagesize($file_nm);
        if (false === $imageInfo) {
            $err_msg[] = 'ファイルの指定が不適切です。';
        } else {
            // 画像種類の判定
            switch ($imageInfo[2]) {
                case IMAGETYPE_JPEG:
                    break;

                case IMAGETYPE_PNG:
                    break;
                          
                default:
                    $err_msg[] = 'ファイル形式はjpegかpngのみです';
            }
        }
    }
    return $err_msg;
}

/**
* サーチ_バリデーション(trim必須)
* @parm str $serch サーチ対象
* @return str $err_msg エラーメッセージ
*/
function serch_validation($serch) {
    
    $err_msg = array();
    
    return $err_msg;
}

/**
* 拡張子取得
* @parm str $temp_name 一時ファイル名
* @return str $extension 拡張子
*/
function get_extension($tmp_name) {
        
        $extension = null;
        if (!empty($tmp_name)){
        // 学習メモ:"getimagesize"は拡張子を返してくれる
        $imageInfo = getimagesize($tmp_name);
    
            // 画像種類の判定
            switch ($imageInfo[2]) {
                case IMAGETYPE_JPEG:
                    $extension = 'jpg';
                    break;

                case IMAGETYPE_PNG:
                    $extension = 'png';
                    break;
            }
        }
    return $extension;
}

/**
* レングスチェック(ブランク許容版)
* @parm str $str 文字列チェック前ストリング
* @parm int $len 文字列最大レングス
* @parm str $word チェック項目名
* @parm str $space ブランクを許容するか否か
* @return array str $err_msg エラーメッセージ
*/
function check_length2($str, $len, $word, $space) {
    
    $err_msg = null;
    
    if (!empty($str)) {
        // PHP上の改行マークをブランクに置き換える
        $str = str_replace(array("\r\n","\n","\r"),"",$str);
        $iCount = mb_strlen( $str, "UTF-8" );
        if ($iCount > $len) {
            $err_msg = $word .'の文字数は' .$len .'文字以下で入力してください。入力した文字数:' . $iCount;
        }
    } else {
        if ($space === false) {
            $err_msg = $word .'：未入力です';
        }
    }
    return $err_msg;
}

/**
* ダイレクトメッセージ_バリデーション(trim必須)
* @parm str $tweet ツイート内容
* @return str $err_msg エラーメッセージ
*/
function DM_validation($dm) {
    
    $err_msg = array();
    
    $trim_dm = trim_and_entity($dm);
    
    // ダイレクトメッセージ
    $msg = check_length2($dm, 1000, 'メッセージ本文', false);
    if (!empty($msg)) {
        $err_msg[] = $msg;
    }
    return $err_msg;
}
