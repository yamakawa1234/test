<?php
 
// MySQL接続情報 
$host   = 'localhost';      // データベースのホスト名又はIPアドレス ※CodeCampでは「localhost」で接続できます
$user   = 'codecamp6475';   // MySQLのユーザ名
$passwd = 'PCTZVCRT';       // MySQLのパスワード
$dbname = 'codecamp6475';   // データベース名
 
$data            = array(); // INSERT用の配列
$msg             = array(); // 通常メッセージ
$err_msg         = array(); // エラーメッセージ
$vending_info    = array(); // 商品テーブル
$macthes         = array();
$status_msg      = '';      // ステータスボタンのメッセージ
$str             = '';      // トリム用変数
$new_name        = '';      // トリム用変数
$new_price       =  0;      // トリム用変数
$new_stock       =  0;      // トリム用変数
$last_id         =  0;

$regext = '/(jpg|jpeg|png)/'; // 
$regexp = '/^[0-9]{1,11}$/'; // int型の桁数


// コネクション取得
if ($link = mysqli_connect($host, $user, $passwd, $dbname)) {

    // 文字コードセット
    mysqli_set_charset($link, 'UTF8');
    
    // 現在時刻を取得
    $date = date('Y-m-d H:i:s');
    
    // POSTでこのページをオープンしたか否か
    if (!empty($_POST['sql_kind'])) {
        // 新しいデータを入力
        if ($_POST['sql_kind'] === 'insert') {
            
            // 文字列トリム
            $new_name  = space_trim ($_POST['new_name']);
            $new_price = space_trim ($_POST['new_price']);
            $new_stock = space_trim ($_POST['new_stock']);
            
            if (empty($new_name)) {
                $err_msg[] = '名前を入力してください';
            }    
            // 正規表現チェック
            // 値段
            if (preg_match($regexp, $new_price, $macthes) != 1) {
                // 不完全一致
                $err_msg[] = '値段は11桁の半角数字を入力してください';
            }
            // 個数    
            if (preg_match($regexp, $new_stock, $macthes) != 1) {
                // 不完全一致
                $err_msg[] = '個数は11桁の半角数字を入力してください';
            }
            //if (!empty($_FILES)) {
                // ファイルが存在するか？
                if (is_uploaded_file($_FILES["new_img"]["tmp_name"])) {
                    // 拡張子取得
                    $file_nm = $_FILES['new_img']['name'];

/* moriyama コメントアウト
                    $extension = pathinfo($file_nm, PATHINFO_EXTENSION);
                    if (preg_match($regext, $extension, $macthes) != 1) {
                        // 不完全一致
                        $err_msg[] = 'ファイル形式はjpegかpngのみです';
                    }    
*/


                    // 画像情報の取得
                    $file_nm = $_FILES['new_img']['tmp_name'];
                    $imageInfo = getimagesize($file_nm);
                    $extension = '';
                    if (false === $imageInfo) {
                        $err_msg[] = 'ファイルの指定が不適切です。';
                    } else {
                        // 画像種類の判定
                        switch ($imageInfo[2]) {
                          case IMAGETYPE_JPEG:
                            $extension = 'jpg';
                            break;

                          case IMAGETYPE_PNG:
                            $extension = 'png';
                            break;
                          
                          default:
                            $err_msg[] = 'ファイル形式はjpegかpngのみです';
                        }
                    }
                } else {
                    $err_msg[] = 'ファイルが選択されていません';
                }    
            //} else {
                //$err_msg[] = 'ファイルが選択されていません1';
            //}
            
            // 更新系の処理を行う前にトランザクション開始(オートコミットをオフ）
            mysqli_autocommit($link, false);

            if (empty($err_msg)) {
                
                $data = array();
                // 挿入情報をまとめる
                $data = array(
                'ドリンク名'        => $new_name,
                '値段'              => $new_price,
                '作成日'            => $date,
                '更新日'            => $date,
                '公開ステータス'    => $_POST['new_status'],
// moriyama 拡張子だけ保存するように修正
                'picture_name'      => $extension
                );
                
                // SQL
                $sql  = 'INSERT INTO `ドリンク情報`(`ドリンク名`, `値段`, `作成日`, `更新日`, `公開ステータス`, `picture_name`) ';
                $sql .= 'VALUE (\'' . implode('\',\'', $data) . '\');';
                if ($result = mysqli_query($link, $sql)) {
                    // 学習メモ:mysqlではなくmysqliなので注意すること！
                    $last_id = mysqli_insert_id($link);
                    
                    $data = array();
                    // 挿入情報をまとめる
                    $data = array(
                    'ドリンクID'        => $last_id,
                    '在庫数'            => $new_stock,
                    '作成日'            => $date,
                    '更新日'            => $date
                    );

                
                    $sql  = 'INSERT INTO `在庫数管理`(`ドリンクID`, `在庫数`, `作成日`, `更新日`) ';
                    $sql .= 'VALUE (\'' . implode('\',\'', $data) . '\');';
                    if ($result = mysqli_query($link, $sql)) {
// moriyama $last_idを使ってファイル名を生成
// moriyama 生成したファイル名でmove_uploaded_file()を実行


                        // 画像のアップロード
                        // 学習メモ:move_uploaded_file
                        // 画像の移動＆名前の変更ができる関数
                        if (!move_uploaded_file($_FILES["new_img"]["tmp_name"], "./pict/" .$last_id .'.' .$extension)) {
                            $err_msg[] = 'ファイルをアップロードできません';
                        }    
                    } else {
                        $err_msg[] = 'SQL失敗:' . $sql;
                    }
                } else {
                    $err_msg[] = 'SQL失敗:' . $sql;
                }
                
                // トランザクション成否判定
                if (count($err_msg) === 0) {
                // 処理確定
                    mysqli_commit($link);
                    $msg[] = '追加完了！';
                } else {
                    // 処理取消
                    mysqli_rollback($link);
                }
            }    
        }
        
        // 在庫数操作
        if ($_POST['sql_kind'] === 'update') {
            
            // 文字列トリム
            $new_stock = space_trim ($_POST['update_stock']);
            // 正規表現チェック
            // 個数    
            if (preg_match($regexp, $new_stock, $macthes) != 1) {
                    // 不完全一致
                    $err_msg[] = '個数は11桁の半角数字を入力してください';
            }
            
            if (empty($err_msg)) {
                
            // SQL
            $sql  = 'UPDATE `在庫数管理` ';
            $sql .= 'SET `在庫数`=' .$new_stock .' ';
            $sql .= 'WHERE `ドリンクID`=' .$_POST['drink_id'] .';';
            if ($result = mysqli_query($link, $sql)) {
                    $msg[] = '在庫数変更完了';
                } else {
                    $err_msg[] = 'SQL失敗:' . $sql;
                }
            }
        }    
            
        //  ステータス操作
        if ($_POST['sql_kind'] === 'change') {
            
            // ステータス判定
            if ($_POST['change_status'] === '1') {
                    $status  = '0';    
            } else {
                    $status  = '1';
            }
            
            // SQL
            $sql  = 'UPDATE `ドリンク情報` ';
            $sql .= 'SET `公開ステータス`=' .$status .' ';
            $sql .= 'WHERE `ドリンクID`=' .$_POST['drink_id'] .';';
            if ($result = mysqli_query($link, $sql)) {
                $msg[] = 'ステータス情報変更完了';
            } else {
                $err_msg[] = 'SQL失敗:' . $sql;
            }
        }
    }
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
            $vending_info[$i]['drink_id']      = $row['ドリンクID'];
            $vending_info[$i]['name']          = $row['ドリンク名'];
            $vending_info[$i]['price']         = $row['値段'];
            $vending_info[$i]['status']        = $row['公開ステータス'];
            $vending_info[$i]['stock']         = $row['在庫数'];
            $vending_info[$i]['picture_name']  = $row['picture_name'];
            //htmlspecialcharsのpoint
            //サニタイズ(無毒化)
            //データベースに格納するときに行うのが好ましい
            //updateやinsertを行う際はhtmlspecialcharsを使用すること
            $i++;
        }
    } else {
        $err_msg[] = 'SQL失敗:' . $sql;
    }
}

if (!empty($err_msg)) {
    foreach ($err_msg as $print)
    print($print .'<br />'); // 通常のコメント
}
if (!empty($msg)) {
    foreach ($msg as $print)
    print($print .'<br />'); // 通常のコメント
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
    <meta charset="utf-8">
    <title>自動販売機</title>
    <style>
        section {
            margin-bottom: 20px;
            border-top: solid 1px;
        }

        table {
            width: 660px;
            border-collapse: collapse;
        }

        table, tr, th, td {
            border: solid 1px;
            padding: 10px;
            text-align: center;
        }

        caption {
            text-align: left;
        }

        .text_align_right {
            text-align: right;
        }

        .drink_name_width {
            width: 100px;
        }

        .input_text_width {
            width: 60px;
        }

        .status_false {
            background-color: #A9A9A9;
        }
        .pic {
            width: 100px;
            height: 150px;
        }
    </style>
</head>
<body>
    <h1>自動販売機管理ツール</h1>
    <section>
        <h2>新規商品追加</h2>
        <form method="post" enctype="multipart/form-data">
            <div><label>名前: <input type="text" name="new_name" value=""></label></div>
            <div><label>値段: <input type="text" name="new_price" value=""></label></div>
            <div><label>個数: <input type="text" name="new_stock" value=""></label></div>
            <div><input type="file" name="new_img"></div>
            <div>
                <select name="new_status">
                    <option value="0">非公開</option>
                    <option value="1">公開</option>
                </select>
            </div>
            <input type="hidden" name="sql_kind" value="insert">
            <div><input type="submit" value="■□■□■商品追加■□■□■"></div>
        </form>
    </section>
    <section>
        <h2>商品情報変更</h2>
        <table>
            <caption>商品一覧</caption>
            <tr>
                <th>商品画像</th>
                <th>商品名</th>
                <th>価格</th>
                <th>在庫数</th>
                <th>ステータス</th>
            </tr>
            <?php   foreach ($vending_info as $info) { 
                if ($info['status'] === '1') {
                    $status_msg  = '公開→非公開';    
                } else {
                    $status_msg  = '非公開→公開';
                } ?>
            <tr>
                <form method="post">
                    <td><img class="pic" src="./pict/<?php print $info['drink_id'] .'.' .$info['picture_name'] ; ?>"></td>
                    <td class="drink_name_width"><?php print $info['name']; ?></td>
                    <td class="text_align_right"><?php print $info['price']; ?></td>
                    <td><input type="text"  class="input_text_width text_align_right" name="update_stock" value="<?php print $info['stock']; ?>">個&nbsp;&nbsp;<input type="submit" value="変更"></td>
                    <input type="hidden" name="drink_id" value="<?php print $info['drink_id']; ?>">
                    <input type="hidden" name="sql_kind" value="update">
                </form>
                <form method="post">
                    <td><input type="submit" value="<?php print $status_msg; ?>"></td>
                    <input type="hidden" name="change_status" value="<?php print $info['status']; ?>">
                    <input type="hidden" name="drink_id" value="<?php print $info['drink_id']; ?>">
                    <input type="hidden" name="sql_kind" value="change">
                </form>
            </tr>    
            <?php   } ?>
        </table>
    </section>
</body>
</html>