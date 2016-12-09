<?php

define('DB_HOST',   'localhost');       // データベースのホスト名又はIPアドレス
define('DB_USER',   'codecamp6475');    // MySQLのユーザ名
define('DB_PASSWD', 'PCTZVCRT');        // MySQLのパスワード
define('DB_NAME',   'codecamp6475');    // データベース名
 
define('HTML_CHARACTER_SET', 'UTF-8');  // HTML文字エンコーディング
define('DB_CHARACTER_SET',   'UTF8');   // DB文字エンコーディング

$msg        = array();
$err_msg    = array();

// DB接続
$link = get_db_connect();

//REQUEST_METHOD:いまのページがPOST or GETによってオープンしたかどうか
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // 名前チェック
    if (check_name($_POST['name']) != TRUE) {
        $err_msg[] = 'お名前は１〜２０文字で入力してください';
    }
    

    // コメントチェック
    if (check_comment($_POST['comment']) != TRUE) {
        $err_msg[] = '発言は１〜１００文字で入力してください';
    }
    
    if (empty($err_msg)) {
        
        // 現在時刻を取得
        $date = date('Y-m-d H:i:s');

        $boolean = db_insert($link, $_POST['name'], $date, $_POST['comment']);
        if ($boolean = true) {
            $msg    = '発　言　成　功';
        } else {
            $err_msg    = '発　言　失　敗';
        }
        
    }

}    

// 商品の一覧を取得
$bbs_data = db_select($link);
 
// DB切断
close_db_connect($link);
 
 
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
* DBへinsert
*/
function db_insert($link, $new_name, $date, $comment) {
 
    $data = array();
    
    // 挿入情報をまとめる
    $data = array(
        'name'              => $new_name,
        'date'              => $date,
        'comment'           => $comment,
        );
    
    $entity_data = entity_assoc_array($data);
                
    // SQL
    $sql  = 'INSERT INTO `bbs_table`(`name`, `date`, `comment`) ';
    $sql .= 'VALUE (\'' . implode('\',\'', $entity_data) . '\');';

    // クエリ実行
    $result = mysqli_query($link, $sql);

    return $result;
    
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
* DBとのコネクション切断
* @param obj $link DBハンドル
*/
function close_db_connect($link) {
    // 接続を閉じる
    mysqli_close($link);
}
 
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
        // 特殊文字をHTMLエンティティに変換
        $assoc_array[$key] = entity_str($assoc_array[$key]);
        
    }
    return $assoc_array;
    
}

function space_trim ($str) {
    // 行頭の半角、全角スペースを、空文字に置き換える
    $str = preg_replace('/^[ 　]+/u', '', $str);
 
    // 末尾の半角、全角スペースを、空文字に置き換える
    $str = preg_replace('/[ 　]+$/u', '', $str);
 
    return $str;
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ひとこと掲示板</title>
</head>
<body>
<?php if (count($msg) > 0) { ?>
<?php     foreach ((array)$msg as $value) { ?>
    <p><?php print $value; ?></p>
<?php     } ?>
<?php } ?>
<?php if (count($err_msg) > 0) { ?>
<?php     foreach ((array)$err_msg as $value) { ?>
    <p><?php print $value; ?></p>
<?php     } ?>
<?php } ?>
    <h1>みんなの意見交換所</h1>
 
    <form method="post">
        <label>お名前:<input type="text" name="name"><br>
        発言　:<input type="text" name="comment"><br>
        <input type="submit" name="submit" value="発言する"></label>
    </form>
    
    <table>
    <caption>発言一覧</caption>
<?php foreach ($bbs_data as $read) { ?>
    <tr>
        <td><?php print $read['name']; ?></td>
        <td><?php print $read['date']; ?></td>
        <td><?php print $read['comment']; ?></td>
    </tr>
<?php } ?>
    </table>
</body>
</html>