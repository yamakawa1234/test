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
* ブランクチェック
* @param str $str 文字列チェック前名前
* @return boolean true or false
*/
function check_blank($str) {
    
    // 文字列トリム
    $trim_name      = space_trim ($str);
    
    if (!empty($str)) {
        $boolean = true;
    } else {
        $boolean = false;
    }
    return $boolean;
}

/**
* 名前入力チェック
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
* int型入力チェック
* @param int $int int型チェック前項目
* @param str $word チェック項目名
* @param str $regexp 正規表現条件
* @param str $msg エラー時のメッセージ 
* @return array str $err_msg エラーメッセージ
*/
function check_int($int, $word, $regexp, $msg) {
    
    $err_msg = array();
    // 文字列トリム
    $trim_int          = space_trim ($int);
    
    if (!empty($int)) {
        
    if (preg_match($regexp, $trim_int, $macthes) != 1) {
        $err_msg = $word .$msg;
    }
    } else {
        $err_msg = $word .'を入力してください';
    }
    return $err_msg;
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
* @param str $price 値段
* @param str $stock 個数
* @param str $status 公開ステータス
* @param str $tmp_name 選択したファイル
* @return str $err_msg エラーメッセージ
*/
function validation($name, $price, $stock, $status,$tmp_name) {
    
    $err_msg = array();
    
    $msg = check_length($name, 20,  'ドリンク名');
    if (!empty($msg)) {
        $err_msg[] = $msg;
    }
    
    $regexp = '/^[0-9]{1,11}$/';
    $msg = 'は11桁以下の整数を入力してください';
    
    $msg = check_int($price, '値段', $regexp, $msg);
    if (!empty($msg)) {
        $err_msg[] = $msg;
    }
    
    $msg = check_int($stock, '個数', $regexp, $msg);
    if (!empty($msg)) {
        $err_msg[] = $msg;
    }
    
    // 公開ステータスチェック
    if ($status!="0"&&$status!="1") {
        $err_msg[] = 'ファイル公開ステータスが異常値です';
    }
    
    // ファイルが存在するか？
    if (is_uploaded_file($tmp_name)) {
        
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
    } else {
        $err_msg[] = 'ファイルが選択されていません';
    }
    return $err_msg;
}

/**
* update_バリデーション
* @param obj $link DBハンドル
* @param str $drink_id ドリンクID
* @param str $stock 個数
* @param str $date 日付
* @return str $err_msg エラーメッセージ
*/
function update_validation($link, $drink_id, $stock, $date) {
    
    $err_msg = array();
    
    $regexp = '/^[0-9]{1,11}$/';
    $msg = 'は11桁以下の整数を入力してください';
    
    $msg = check_int($stock, '個数', $regexp, $msg);
    if (!empty($msg)) {
        $err_msg[] = $msg;
    }
    
    if (empty($err_msg)) {
                
        // SQL
        $sql  = 'UPDATE `在庫数管理` ';
        $sql .= 'SET `在庫数`=' .$stock .',`更新日`=\'' .$date .'\' ';
        $sql .= 'WHERE `ドリンクID`=' .$drink_id .';';
        if (false == mysqli_query($link, $sql)) {
            $err_msg[] = 'SQL失敗:' . $sql;
        }
        //print $sql .'\n';
        //print $err_msg[0] .'\n';
    }
    return $err_msg;
}

/**
* change_バリデーション
* @param obj $link DBハンドル
* @param str $drink_id ドリンクID
* @param str $sattus ステータス
* @return str $err_msg エラーメッセージ
*/
function change_validation($link, $drink_id, $status) {
    
    $err_msg = array();
    
    // ステータス判定
    if ($status === '1') {
        $status  = '0';    
    } else {
        $status  = '1';
    }
            
    // SQL
    $sql  = 'UPDATE `ドリンク情報` ';
    $sql .= 'SET `公開ステータス`=' .$status .' ';
    $sql .= 'WHERE `ドリンクID`=' .$drink_id .';';
    if (false == mysqli_query($link, $sql)) {
        $err_msg[] = 'SQL失敗:' . $sql;
    }
    return $err_msg;
}

/**
* 拡張子取得
* @param str $name ファイル名
* @param str $comment コメント
* @return str $err_msg エラーメッセージ
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
* ドリンク情報_insert
* @param obj $link DBハンドル
* @param str $name ドリンク名
* @param int $price 値段
* @param str $date 日付
* @param str $status 公開ステータス
* @param str $extension 拡張子
* @return int $last_id プライマリキー
*/
function drink_info_insert($link, $name, $price, $date, $status, $extension) {
    
    $last_id = null;
    $data    = array();
    // 特殊文字をHTMLエンティティに変換
    $name = entity_str($name); 
    // 挿入情報をまとめる
    $data = array(
    'ドリンク名'        => $name,
    '値段'              => $price,
    '作成日'            => $date,
    '更新日'            => $date,
    '公開ステータス'    => $status,
    'picture_name'      => $extension,
    );
    
    // SQL
    $sql  = 'INSERT INTO `ドリンク情報`(`ドリンク名`, `値段`, `作成日`, `更新日`, `公開ステータス`, `picture_name`) ';
    $sql .= 'VALUE (\'' . implode('\',\'', $data) . '\');';
    if ($result = mysqli_query($link, $sql)) {
        $last_id = mysqli_insert_id($link);
    }
    return $last_id;
}

/**
* 在庫数管理_insert
* @param obj $link DBハンドル
* @param int $last_id ドリンクID
* @param str $stock 初期在庫
* @param str $date 日付
* @param str $tmp_name サーバーに一時保存された画像の名前
* @param str $extension 拡張子
* @return str $err_msg エラーメッセージ
*/
function stock_insert($link, $last_id, $stock, $date, $tmp_name, $extension) {
    
    // var_dump(func_get_args());
    $err_msg = array();
    if ($last_id != null) {
        $data = array();
        // 挿入情報をまとめる
        $data = array(
        'ドリンクID'        => $last_id,
        '在庫数'            => $stock,
        '作成日'            => $date,
        '更新日'            => $date
        );
        
        $sql  = 'INSERT INTO `在庫数管理`(`ドリンクID`, `在庫数`, `作成日`, `更新日`) ';
        $sql .= 'VALUE (\'' . implode('\',\'', $data) . '\');';
        if ($result = mysqli_query($link, $sql)) {
            // 画像のアップロード
            if (!move_uploaded_file($tmp_name, "./pict/" .$last_id .'.' .$extension)) {
                $err_msg[] = 'ファイルをアップロードできません';
            }    
        } else {
            $err_msg[] = 'SQL失敗:' . $sql;
        }
    } else {
        $err_msg[] = 'insert_idが取得できていません';
    }
    return $err_msg;
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
* get_db
* @param obj $link DBハンドル
* @return str $vending_info ドリンク情報
*/
function get_db($link) {
    
    $vending_info = array();
    // SQL
    $sql  = 'SELECT ドリンク情報.ドリンクID, ドリンク情報.ドリンク名, ドリンク情報.値段, ドリンク情報.公開ステータス, ドリンク情報.picture_name,在庫数管理.在庫数 ';
    $sql .= 'FROM ドリンク情報 ';
    $sql .= 'LEFT JOIN 在庫数管理 ON ドリンク情報.ドリンクID = 在庫数管理.ドリンクID  ';
    $sql .= 'ORDER BY ドリンク情報.ドリンクID ASC;';

    // クエリ実行
    if ($result = mysqli_query($link, $sql)) {
        $i = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            $vending_info[$i]['drink_id']      = $row['ドリンクID'];
            $vending_info[$i]['name']          = $row['ドリンク名'];
            $vending_info[$i]['price']         = $row['値段'];
            $vending_info[$i]['status']        = $row['公開ステータス'];
            $vending_info[$i]['stock']         = $row['在庫数'];
            $vending_info[$i]['picture_name']  = $row['picture_name'];
            $i++;
        }
        mysqli_free_result($result);
    }
    return $vending_info;
}

/**
* db_close
* @param obj $link DBハンドル
*/
function db_close($link) {
    mysqli_close($link);
}

/**
* get_db_vender
* @param obj $link DBハンドル
* @return str $vending_info ドリンク情報
*/
function get_db_vender($link) {
    
    $vending_info = array();
    /**
     * 商品情報を取得
     */
    // SQL
    $sql  = 'SELECT ドリンク情報.ドリンクID, ドリンク情報.ドリンク名, ドリンク情報.値段, ドリンク情報.公開ステータス, ドリンク情報.picture_name,在庫数管理.在庫数 ';
    $sql .= 'FROM ドリンク情報 ';
    $sql .= 'LEFT JOIN 在庫数管理 ON ドリンク情報.ドリンクID = 在庫数管理.ドリンクID  ';
    $sql .= 'ORDER BY ドリンク情報.ドリンクID ASC;';

    // クエリ実行
    if ($result = mysqli_query($link, $sql)) {
        $i = 0;
        while ($row = mysqli_fetch_assoc($result)) {
            
            // 公開ステータスが非公開のものに対しては、公開しない
            if ($row['公開ステータス'] === '1') {
                $vending_info[$i]['drink_id']      = $row['ドリンクID'];
                $vending_info[$i]['name']          = $row['ドリンク名'];
                $vending_info[$i]['price']         = $row['値段'];
                $vending_info[$i]['status']        = $row['公開ステータス'];
                $vending_info[$i]['stock']         = (int)$row['在庫数'];
                $vending_info[$i]['picture_name']  = $row['picture_name'];
                $i++;
            }
        }
        mysqli_free_result($result);
    }
    return $vending_info;
}

/**
* get_date
* @return str $date 現在時刻
*/
function get_date() {
    // 現在時刻を取得
    $date = date('Y-m-d H:i:s');
    return $date;
}

/**
* 値段チェック
* @param int $int int型チェック前項目
* @param str $word チェック項目名
* @param str $regexp 正規表現条件
* @return str $err_msg エラーメッセージ
*/
function check_money($money, $word, $regexp) {
    
    $err_msg = array();
    
    // 文字列トリム
    $trim_money = space_trim ($money);

    if (!empty($trim_money)) {
        if (preg_match($regexp, $trim_money, $macthes) === 1) {
            if ($trim_money > 10000) {
                $err_msg = '値段は10,000円以下を入力してください';
            } else {    
                if ($trim_money <= 0) {
                $err_msg = '値段は正の整数を入力してください';
                }
            }    
        } else {
            $err_msg = '値段は10,000円以下の半角数字を入力してください';
        }
    } else {
        $err_msg = '値段を入力してください';
    }       
    return $err_msg;
}

/**
* バリデーション_result
* @param obj $link DBハンドル
* @param str $name 名前
* @param str $price 値段
* @param str $stock 個数
* @param str $status 公開ステータス
* @return str $err_msg エラーメッセージ
*/
function validation_result($link, $money, $drink_id) {
    
    $err_msg = array();
    $msg = '';

    if (isset($money)) {
        $regexp = '/^[0-9]{1,5}$/';
        
        $msg = check_money($money, '値段', $regexp);
        if (!empty($msg)) {
            $err_msg[] = $msg;
        }
                
        if (empty($drink_id)) {
            $err_msg[] = '商品を選択してください';
        }
    }
        return $err_msg;
}

/**
* get_db_match
* @param obj $link DBハンドル
* @param str $money 値段
* @param str $drink_id
* @return str $err_msg エラーメッセージ

*/
/*function get_db_match($link, $money, $drink_id, $date) {
    
    $err_msg = array();
    
    // SQL
    $sql  = 'SELECT ドリンク情報.ドリンクID, ドリンク情報.ドリンク名, ドリンク情報.値段, ドリンク情報.公開ステータス, ドリンク情報.picture_name, 在庫数管理.在庫数 ';
    $sql .= 'FROM ドリンク情報 ';
    $sql .= 'LEFT JOIN 在庫数管理 ON ドリンク情報.ドリンクID = 在庫数管理.ドリンクID  ';
    $sql .= 'WHERE ドリンク情報.ドリンクID=' .$drink_id .';';
    
    // クエリ実行
    if ($result = mysqli_query($link, $sql)) {
        // １件取得
        $row = mysqli_fetch_assoc($result);
                    
        mysqli_free_result($result);
                    
        if ($row['値段'] <= $money) {
            
            $new_stock = (int)$row['在庫数'] - 1;
            if ($new_stock < 0) {
                $err_msg[] = '在庫がありません';
            }
            if (empty($err_msg)) {
                $change = $money - (int)$row['値段'];
                    
                // 更新系の処理を行う前にトランザクション開始(オートコミットをオフ）
                mysqli_autocommit($link, false);
                
                // SQL
                $sql  = 'UPDATE `在庫数管理` ';
                $sql .= 'SET `在庫数`=' .$new_stock .',`更新日`=\'' .$date .'\' ';
                $sql .= 'WHERE `ドリンクID`=' .$drink_id .';';
                if ($result = mysqli_query($link, $sql)) {
                    $data = array();
                    
                    // 挿入情報をまとめる
                    $data = array(
                    'ドリンクID'        => $drink_id,
                    '購入履歴'          => $date,
                    );
                    
                    $sql  = 'INSERT INTO `購入履歴`(`ドリンクID`, `購入履歴`) ';
                    $sql .= 'VALUE (\'' . implode('\',\'', $data) . '\');';
                    if ($result != mysqli_query($link, $sql)) {
                        $err_msg[] = 'SQL失敗:' . $sql;
                    }
                }
            }
        } else {
            $err_msg[] = 'お金が足りません';
        }
    } else {
        $err_msg[] = 'SQL失敗:' . $sql;
    }
    // トランザクション成否判定
    if (count($err_msg) === 0) {
        // 処理確定
        mysqli_commit($link);
        $msg[] = '<img class="pict" src="./pict/' .$row['ドリンクID'] .'.' .$row['picture_name'] .'">';
        $msg[] = 'がしゃん！ ' .$row['ドリンク名'] .' を買いました';
        $msg[] = 'おつりは ' .$change .' 円です';
    } else {
        // 処理取消
        mysqli_rollback($link);
    }
    return $err_msg;
}*/

function get_db_match2($link, $money, $drink_id, $date) {
    
    $row = array();
    
    // SQL
    $sql  = 'SELECT ドリンク情報.ドリンクID, ドリンク情報.ドリンク名, ドリンク情報.値段, ドリンク情報.公開ステータス, ドリンク情報.picture_name, 在庫数管理.在庫数 ';
    $sql .= 'FROM ドリンク情報 ';
    $sql .= 'LEFT JOIN 在庫数管理 ON ドリンク情報.ドリンクID = 在庫数管理.ドリンクID  ';
    $sql .= 'WHERE ドリンク情報.ドリンクID=' .$drink_id .';';
    
    // クエリ実行
    if ($result = mysqli_query($link, $sql)) {
        // １件取得
        $row = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
    }
    return $row;
}

function get_db_match3($link, $money, $drink_id, $date, $row) {
    
    $err_msg = array();
    
    // 文字列トリム
    $money = space_trim ($money);
    
    if ($row['値段'] <= $money) {
            
        $new_stock = (int)$row['在庫数'] - 1;
        if ($new_stock < 0) {
            $err_msg[] = '在庫がありません';
        }
        if (empty($err_msg)) {
            $change = $money - (int)$row['値段'];

            // 更新系の処理を行う前にトランザクション開始(オートコミットをオフ）
            mysqli_autocommit($link, false);
            
            // SQL
            $sql  = 'UPDATE `在庫数管理` ';
            $sql .= 'SET `在庫数`=' .$new_stock .',`更新日`=\'' .$date .'\' ';
            $sql .= 'WHERE `ドリンクID`=' .$drink_id .';';
            
            if ($result = mysqli_query($link, $sql)) {
                
                $data = array();
                
                // 挿入情報をまとめる
                $data = array(
                'ドリンクID'        => $drink_id,
                '購入履歴'          => $date,
                );
                    
                $sql  = 'INSERT INTO `購入履歴`(`ドリンクID`, `購入履歴`) ';
                $sql .= 'VALUE (\'' . implode('\',\'', $data) . '\');';
                if ($result != mysqli_query($link, $sql)) {
                    $err_msg[] = 'SQL失敗:' . $sql;
                }
            }
            
            // トランザクション成否判定
            if (count($err_msg) === 0) {
                // 処理確定
                mysqli_commit($link);
                $err_msg[] = '<img class="pict" src="./pict/' .$row['ドリンクID'] .'.' .$row['picture_name'] .'">';
                $err_msg[] = 'がしゃん！ ' .$row['ドリンク名'] .' を買いました';
                $err_msg[] = 'おつりは ' .$change .' 円です';
            } else {
                // 処理取消
                mysqli_rollback($link);
            }
        }
        
    } else {
        $err_msg[] = 'お金が足りません';
    }
    return $err_msg;
}