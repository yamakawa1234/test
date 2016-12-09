<?php
 
// MySQL接続情報 
$host   = 'localhost';      // データベースのホスト名又はIPアドレス ※CodeCampでは「localhost」で接続できます
$user   = 'codecamp6475';   // MySQLのユーザ名
$passwd = 'PCTZVCRT';       // MySQLのパスワード
$dbname = 'codecamp6475';   // データベース名
 
$msg             = array(); // 通常メッセージ
$err_msg         = array(); // エラーメッセージ
$vending_info    = array(); // 商品テーブル
$macthes         = array();
$change          = '';      // おつり
$str             = '';      // トリム用変数
$new_stock       =  0;      // トリム用変数

$regexp = '/^[0-9]{1,5}$/'; // int型の桁数


// コネクション取得
if ($link = mysqli_connect($host, $user, $passwd, $dbname)) {

    // 文字コードセット
    mysqli_set_charset($link, 'UTF8');
    
    // 現在時刻を取得
    $date = date('Y-m-d H:i:s');
    
    // POSTでこのページをオープンしたか否か
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['money'])) {
            // 文字列トリム
            $money = space_trim ($_POST['money']);
            
            // 正規表現チェック
            // 値段
            if (preg_match($regexp, $money, $macthes) === 1) {
                if ($money > 10000) {
                    $err_msg[] = '値段は10,000円以下を入力してください';
                } else {    
                    if ($money <= 0) {
                        $err_msg[] = '値段は正の整数を入力してください';
                    }
                }    
            } else {
                $err_msg[] = '値段は10,000円以下の半角数字を入力してください';
            }
            
            if (empty($_POST['drink_id'])) {
                $err_msg[] = '商品を選択してください';
            }
            
            if (empty($err_msg)) {
                
                // SQL
                $sql  = 'SELECT ドリンク情報.ドリンクID, ドリンク情報.ドリンク名, ドリンク情報.値段, ドリンク情報.公開ステータス, ドリンク情報.picture_name, 在庫数管理.在庫数 ';
                $sql .= 'FROM ドリンク情報 ';
                $sql .= 'LEFT JOIN 在庫数管理 ON ドリンク情報.ドリンクID = 在庫数管理.ドリンクID  ';
                $sql .= 'WHERE ドリンク情報.ドリンクID=' .$_POST['drink_id'] .';';
                
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
                            $sql .= 'SET `在庫数`=' .$new_stock .' ';
                            $sql .= 'WHERE `ドリンクID`=' .$_POST['drink_id'] .';';
                            if ($result = mysqli_query($link, $sql)) {
                                $data = array();
                                
                                // 挿入情報をまとめる
                                $data = array(
                                'ドリンクID'        => $_POST['drink_id'],
                                '購入履歴'          => $date
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
        } else {
            $err_msg[] = '値段を入力してください';
        }
    } else {
        $err_msg[] = 'ページ遷移ミス';
    }
}    
        
function space_trim ($str) {
    // 行頭の半角、全角スペースを、空文字に置き換える
    $str = preg_replace('/^[ 　]+/u', '', $str);
 
    // 末尾の半角、全角スペースを、空文字に置き換える
    $str = preg_replace('/[ 　]+$/u', '', $str);
 
    return $str;
}

/**
* 特殊文字をHTMLエンティティに変換する(2次元配列の値)
* @param array  $assoc_array 変換前配列
* @return array 変換後配列
*/
function entity_assoc_array($assoc_array) {
 
    foreach ((array)$assoc_array as $key => $value) {
        print 'key: ' .$key .'</br>';
        print 'value: ' .$value .'</br>';
        // 特殊文字をHTMLエンティティに変換
        $assoc_array[$key] = entity_str($assoc_array[$key]);
        
    }
    return $assoc_array;
    
}

/**
* 特殊文字をHTMLエンティティに変換する
* @param str  $str 変換前文字
* @return str 変換後文字
*/
function entity_str($str) {
    return htmlspecialchars($str, ENT_QUOTES);
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>自動販売機結果</title>
    <style>
    .pict {
            width: 100px;
            height: 120px;
        }
    </style>
</head>
<body>
    <h1>自動販売機結果</h1>
    <section>
        <?php if (!empty($msg)) {
            foreach ($msg as $print)
            print('<span>' .$print .'</span><br />'); // 通常のコメント
        } ?>
        <?php if (!empty($err_msg)) {
            foreach ($err_msg as $print)
            print('<span>' .$print .'</span><br />'); // エラーメッセージ
        } ?>
    </section>
    <a href="vendingMachine.php">戻る</a>
</body>
</html>