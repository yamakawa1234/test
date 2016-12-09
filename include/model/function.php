<?php
 
/**
* 特殊文字をHTMLエンティティに変換する
* @param str  $str 変換前文字
* @return str 変換後文字
*/
function entity_str($str) {
    return htmlspecialchars($str, ENT_QUOTES, HTML_CHARACTER_SET);
}
 
/**
* 特殊文字をHTMLエンティティに変換する(2次元配列の値)
* @param array  $assoc_array 変換前配列
* @return array 変換後配列
*/
function entity_assoc_array($assoc_array) {
 
    foreach ($assoc_array as $key => $value) {
 
        foreach ($value as $keys => $values) {
            // 特殊文字をHTMLエンティティに変換
            $assoc_array[$key][$keys] = entity_str($values);
        }
 
    }
 
    return $assoc_array;
 
}
 
/**
* DBハンドルを取得
* @return obj $link DBハンドル
*/
function get_db_connect() {
 
    // コネクション取得
    if (!$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWD, DB_NAME)) {
        die('error: ' . mysqli_connect_error());
    }
 
    // 文字コードセット
    mysqli_set_charset($link, DB_CHARACTER_SET);
 
    return $link;
}
 
/**
* DBとのコネクション切断
* @param obj $link DBハンドル
*/
function close_db_connect($link) {
    // 接続を閉じる
    mysqli_close($link);
}
 
/**
* クエリを実行しその結果を配列で取得する
*
* @param obj  $link DBハンドル
* @param str  $sql SQL文
* @return array 結果配列データ
*/
function get_as_array($link, $sql) {
 
    // 返却用配列
    $data = array();
 
    // クエリを実行する
    if ($result = mysqli_query($link, $sql)) {
 
        if (mysqli_num_rows($result) > 0) {
 
            // １件ずつ取り出す
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
 
        }
 
        // 結果セットを開放
        mysqli_free_result($result);
 
    }
 
    return $data;
 
}
 
/**
* 商品の一覧を取得する
*
* @param obj $link DBハンドル
* @return array 商品一覧配列データ
*/
function get_goods_table_list($link) {
 
    // SQL生成
    $sql = 'SELECT goods_name, price FROM goods_table';
 
    // クエリ実行
    return get_as_array($link, $sql);
 
}
/**
* insertを実行する
*
* @param obj $link DBハンドル
* @param str SQL文
* @return bool
*/
function insert_db($link, $sql) {
 
    // クエリを実行する
    if (mysqli_query($link, $sql) === TRUE) {
        return TRUE;
    } else {
        return FALSE;
    }
 
}
 
 
/**
* 新規商品を追加する
*
* @param obj $link DBハンドル
* @param str $goods_name 商品名
* @param int $price 価格
* @return bool
*/
function insert_goods_table($link, $goods_name, $price) {
 
    // SQL生成
    $sql = 'INSERT INTO goods_table(goods_name, price) VALUES(\'' . $goods_name . '\', ' . $price . ')';
 
    // クエリ実行
    return insert_db($link, $sql);
 
}
 
 
/**
* リクエストメソッドを取得
* @return str GET/POST/PUTなど
*/
function get_request_method() {
    return $_SERVER['REQUEST_METHOD'];
}
 
 
/**
* POSTデータを取得
* @param str $key 配列キー
* @return str POST値
*/
function get_post_data($key) {
 
    $str = '';
 
    if (isset($_POST[$key]) === TRUE) {
        $str = $_POST[$key];
    }
 
    return $str;
 
}

/**
* 名前チェック
* @param str $name 文字列チェック前名前
* @return boolean true or false
*/
function check_name($name) {
    // 文字列トリム
    $trim_name      = space_trim ($name);
    
    $iCount = mb_strlen( $trim_name, "UTF-8" );
    if ($iCount <= 20) {
        $boolean = true;
    } else {
        $boolean = false;
    }
    return $boolean;
}

/**
* 発言チェック
* @param str $name 文字列チェック前名前
* @return boolean true or false
*/
function check_comment($comment) {
    // 文字列トリム
    $trim_comment   = space_trim ($comment);
    
    $iCount = mb_strlen( $trim_comment, "UTF-8" );
    if ($iCount <= 20) {
        $boolean = true;
    } else {
        $boolean = false;
    }
    return $boolean;
}

/**
* 文字列トリム
* @param str $str トリム前文字列
* @return str トリム後文字列
*/
function space_trim ($str) {
    // 行頭の半角、全角スペースを、空文字に置き換える
    $str = preg_replace('/^[ 　]+/u', '', $str);
 
    // 末尾の半角、全角スペースを、空文字に置き換える
    $str = preg_replace('/[ 　]+$/u', '', $str);
 
    return $str;
}

/**
* DBへinsert
* 
* @param obj  $link DBハンドル
* @param str  $name 名前
* @param str  $date 時間
* @param str  $comment 発言
* @return boolean true or false
*/
function db_insert($link, $name, $date, $comment) {
 
    $data = array();
    
    // 挿入情報をまとめる
    $data = array(
        'name'              => $name,
        'date'              => $date,
        'comment'           => $comment,
        );
    
    $entity_data = entity_assoc_array2($data);
                
    // SQL
    $sql  = 'INSERT INTO `bbs_table`(`name`, `date`, `comment`) ';
    $sql .= 'VALUE (\'' . implode('\',\'', $entity_data) . '\');';

    // クエリ実行
    $result = mysqli_query($link, $sql);

    return $result;
    
}

/**
* DBから全件取得
* @return obj 関数get_as_arrayの返り値 掲示板情報
*/
function db_select($link) {
 
    // SQL
    $sql  = 'SELECT `index`, `name`, `date`, `comment` FROM `bbs_table` ORDER BY `index` ASC;';

    // クエリ実行
    return get_as_array($link, $sql);
    
}

/**
* 特殊文字をHTMLエンティティに変換する(2次元配列の値)
* @param array  $assoc_array 変換前配列
* @return array 変換後配列
*/
function entity_assoc_array2($assoc_array) {
 
    foreach ($assoc_array as $key => $value) {
        // 特殊文字をHTMLエンティティに変換
        $assoc_array[$key] = entity_str($assoc_array[$key]);
        
    }
    return $assoc_array;
    
}

/**
* レングスチェック
* @param str $str 文字列チェック前ストリング
* @param int $len 文字列最大レングス
* @param str $word チェック項目名
* @return array str $err_msg エラーメッセージ
*/
function check_length($str, $len, $word) {
    // 文字列トリム
    $trim_str = space_trim ($str);
    
    $err_msg = null;
    
    if (!empty($trim_str)) {
        $iCount = mb_strlen( $trim_str, "UTF-8" );
        if ($iCount > $len) {
            $err_msg = $word .'の文字数は' .$len .'文字以下で入力してください';
        }
    } else {
        $err_msg = $word .'を入力してください';
    }
    return $err_msg;

}

/**
* バリデーション
* @param str $name 名前
* @param str $comment コメント
* @return str $err_msg エラーメッセージ
*/
function validation($name, $comment) {
    $err_msg = array();
    
    $msg = check_length($name, 20,  'お名前');
    if (!empty($msg)) {
        $err_msg[] = $msg;
    }
    
    $msg = check_length($comment, 100,  '発言');
    if (!empty($msg)) {
        $err_msg[] = $msg;
    }
    
    return $err_msg;

}

/**
* DB登録
* @param obj  $link DBハンドル
* @param str  $name 名前
* @param str  $comment 発言
* @param array $err_msg
* @return boolesn $boolean true or false
*/
function insert_msg($link, $name, $comment, $err_msg) {

    if (empty($err_msg)) {
            
        // 現在時刻を取得
        $date = date('Y-m-d H:i:s');
        
        $boolean = db_insert($link, $name, $date, $comment);
        } else {
            $boolean = false;
        }
    return $boolean;
}