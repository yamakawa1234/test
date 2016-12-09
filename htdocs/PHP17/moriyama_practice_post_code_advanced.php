<?php
 
// 正規表現
$regexp_zipcode = '/^[0-9]{7}$/'; // 郵便番号

// 配列
$msg      = array();
$msg[]    = 'ここに検索結果が表示されます';
$macthes  = array();
$zip_data = array();

// 前後トリム変数
$str     = '';
$zipcode = '';
$address = '';

$key     = 0;
$ini     = 0;
$max     = 9;
$page    = 1;

$host     = 'localhost'; // データベースのホスト名又はIPアドレス ※CodeCampでは「localhost」で接続できます
$username = 'codecamp6475';  // MySQLのユーザ名
$passwd   = 'PCTZVCRT';    // MySQLのパスワード
$dbname   = 'codecamp6475';    // データベース名
 
$link = mysqli_connect($host, $username, $passwd, $dbname);

// 接続成功した場合
if ($link) {
// HINT emptyの時の処理がない場合はNOT演算子を使いましょう。
//    if (empty($_GET['search_method'])) {
    if (!empty($_GET['search_method'])) {
//    } else {

// HINT emptyの時の処理がない場合はFALSEとの比較でもよいです。
//        if (empty($_GET['page'])) {
         if (empty($_GET['page']) === FALSE) {
//       } else {
            $page = $_GET['page'];
        }
        // 郵便番号から検索
        if ($_GET['search_method'] === 'zipcode') {
            // 文字列トリム
            $zipcode = space_trim ($_GET['zipcode']);
            
            if (preg_match($regexp_zipcode, $zipcode, $macthes) === 1) {
                if ($zipcode === $macthes[0]) {
                    // 完全一致
                    // 文字化け防止
                    mysqli_set_charset($link, 'utf8');
                    
                    //クエリ
                    $query  = 'SELECT Zipcode, Prefectures, Municipalities, Area ';
                    $query .= 'FROM zipcode_table ';
                    $query .= 'WHERE Zipcode = \'' .$zipcode .'\'';
                 
                    // クエリを実行します
                    $result = mysqli_query($link, $query);
                    
                    // 1行ずつ結果を配列で取得します
                    while ($row = mysqli_fetch_array($result)) {
                        $zip_data[] = $row;
                    }
                    
                    $msg = array();
                    // ヒット件数が0件の場合、メッセージ出力
                    if (empty ($zip_data)) {
                        $msg[] = '検索結果0件';
                    }
                }
            } else {
                // 不完全一致
                $msg[] = '郵便番号は7桁の半角数字を入力してください';
            }
        }
        // 地名から検索
        if (($_GET['search_method'] === 'address') || (!empty($_GET['page']))){
            if (empty($_GET['pref'])) {
                $msg[] = '都道府県を入力してください。';
            } else {
                if (empty($_GET['address'])) {
                    $msg[] = '市区町村を入力してください。';
                } else {    
                    // 文字列トリム
                    $address = space_trim ($_GET['address']);
                    
                    // 文字化け防止
                    mysqli_set_charset($link, 'utf8');

/* 
HINT ページ遷移について その１
実は、ページ処理はDBから取得するときに行うのが一般的です。
おそらく、まだ習っていないと思いますが、MySQLには limit , offsetという便利な機能があります。
山川さんは理解力も高いのでせっかくなので覚えていただければと思います。

LIMIT句：
最大取得件数を指定。今回の例では10件。 LIMIT句だけの指定も可能

OFFSET句：
何件目から取得するかを指定。1件目からの場合は0を指定する。OFFSETだけの指定はできない。
今回の例では、たとえば2ページ目であれば10を指定
*/
                    //クエリ 山川さんオリジナルの$query
                    $query  = 'SELECT Zipcode, Prefectures, Municipalities, Area ';
                    $query .= 'FROM zipcode_table ';
                    $query .= 'WHERE Prefectures = \'' .$_GET['pref'] .'\' and Municipalities LIKE \'%' .$address .'%\'';

                    //HINT クエリ ページ遷移を考慮した$query (山川さんオリジナルを上書きしています）
                    $offset = $page * 10 - 10;
                    $query  = 'SELECT Zipcode, Prefectures, Municipalities, Area ';
                    $query .= 'FROM zipcode_table ';
                    $query .= 'WHERE Prefectures = \'' .$_GET['pref'] .'\' and Municipalities LIKE \'%' .$address .'%\'';
                    $query .= 'LIMIT 10 OFFSET ' . $offset;

                         
                    // クエリを実行します
                    $result = mysqli_query($link, $query);
                            
                    // 1行ずつ結果を配列で取得します
                    while ($row = mysqli_fetch_array($result)) {
                        $zip_data[] = $row;
                    }
                    
                    // ヒット件数が0件の場合、メッセージ出力
                    $msg = array();
                    if (empty ($zip_data) === FALSE) {
                    } else {
                        $msg[] = '検索結果0件';
                    }

/* 
HINT ページ遷移について その２
DBでページング処理を行う場合、検索結果に何件ヒットしたかがPHP側で把握できないため
別途件数取得のDB検索を行う必要がある。
*/
                    //クエリ 何件あるかを確認するためにcount()関数を利用している
                    $query  = 'SELECT COUNT(*) as data_count ';
                    $query .= 'FROM zipcode_table ';
                    $query .= 'WHERE Prefectures = \'' .$_GET['pref'] .'\' and Municipalities LIKE \'%' .$address .'%\'';

                    // クエリを実行します
                    $result = mysqli_query($link, $query);
                    // HINT count()関数を使っているため必ず1件される。そのためwhileを使う必要がない。
                    $row = mysqli_fetch_array($result);

                    $array_count = $row['data_count'];

                }    
            }
        }
    }
// 接続失敗した場合
} else {
    print 'DB接続失敗';
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
    <title>郵便番号検索</title>
    <style>
        .search_reslut {
            border-top: solid 1px;
            margin-top: 10px;
        }
        
        table {
            border-collapse: collapse;
        }
        table, tr, th, td {
            border: solid 1px;
        }
        caption {
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>郵便番号検索</h1>
    <section>
        <h2>郵便番号から検索</h2>
        <form>
            <input type="text" name="zipcode" placeholder="例）1010001" value="">
            <input type="hidden" name="search_method" value="zipcode">
            <input type="submit" value="検索">
        </form>
        <h2>地名から検索</h2>
        <form id="pr">
            都道府県を選択
            <select name="pref">
                <!-- foreachで都道府県を格納して、$_GETとイコールだったらselectedを付与する！-->
                <option value="" selected>都道府県を選択</option>
                <option value="北海道">北海道</option>
                <option value="青森県">青森県</option>
                <option value="岩手県">岩手県</option>
                <option value="宮城県">宮城県</option>
                <option value="秋田県">秋田県</option>
                <option value="山形県">山形県</option>
                <option value="福島県">福島県</option>
                <option value="茨城県" >茨城県</option>
                <option value="栃木県" >栃木県</option>
                <option value="群馬県" >群馬県</option>
                <option value="埼玉県" >埼玉県</option>
                <option value="千葉県" >千葉県</option>
                <option value="東京都" >東京都</option>
                <option value="神奈川県" >神奈川県</option>
                <option value="新潟県" >新潟県</option>
                <option value="富山県" >富山県</option>
                <option value="石川県" >石川県</option>
                <option value="福井県" >福井県</option>
                <option value="山梨県" >山梨県</option>
                <option value="長野県" >長野県</option>
                <option value="岐阜県" >岐阜県</option>
                <option value="静岡県" >静岡県</option>
                <option value="愛知県" >愛知県</option>
                <option value="三重県" >三重県</option>
                <option value="滋賀県" >滋賀県</option>
                <option value="京都府" >京都府</option>
                <option value="大阪府" >大阪府</option>
                <option value="兵庫県" >兵庫県</option>
                <option value="奈良県" >奈良県</option>
                <option value="和歌山県" >和歌山県</option>
                <option value="鳥取県" >鳥取県</option>
                <option value="島根県" >島根県</option>
                <option value="岡山県" >岡山県</option>
                <option value="広島県" >広島県</option>
                <option value="山口県" >山口県</option>
                <option value="徳島県" >徳島県</option>
                <option value="香川県" >香川県</option>
                <option value="愛媛県" >愛媛県</option>
                <option value="高知県" >高知県</option>
                <option value="福岡県" >福岡県</option>
                <option value="佐賀県" >佐賀県</option>
                <option value="長崎県" >長崎県</option>
                <option value="熊本県" >熊本県</option>
                <option value="大分県" >大分県</option>
                <option value="宮崎県" >宮崎県</option>
                <option value="鹿児島県" >鹿児島県</option>
                <option value="沖縄県" >沖縄県</option>
            </select>
            市区町村
            <input type="text" name="address" value="<?php print $address ?>">
            <input type="hidden" name="search_method" value="address">
            <input type="submit" value="検索">
        </form>
    </section>
    <section class="search_reslut">
<?php
// メッセージ出力
if (empty ($msg) === FALSE) {
    foreach ($msg as $value) {
?>
        <p><?php print htmlspecialchars($value, ENT_QUOTES, 'UTF-8'); ?></p>
<?php
    }
}
// 配列に要素があり、かつ1件以上の検索結果があった場合
if ((empty ($zip_data) === FALSE) && ((empty ($msg) === TRUE))) {
    // 配列数取得
// HINT ページ遷移について その３ DBで件数を取得しているためコメントアウト
//     $array_count = count($zip_data);
    // 総ページ数を取得
    $allPage     = $array_count / 10;
    // 小数点以下を切り上げ
    $allPage     = ceil($allPage);
?>
        <p>検索結果<?php print htmlspecialchars($array_count, ENT_QUOTES, 'UTF-8'); ?>件</p>
        <table>
            <caption>郵便番号検索結果</caption>
            <tr>
                <td>郵便番号</td>
                <td>都道府県</td>
                <td>区市町村</td>
                <td>町域</td>
            </tr>
<?php        
/* 
HINT ページ遷移について その４
DBからの取得時にページング処理をしているため
forではなく、foreachで配列処理をすることが可能。
foreachを使うことで、プログラムがシンプルになる。
*/

    //foreach ($zip_data as $key => $value) {
    //$ini = $page - 1;
    $ini = $page * 10 - 10;
    $max = $ini + 10;
    if ($max > $array_count) {
        $max = $array_count;
    }
    for ($i = $ini; $i < $max; $i++){
?>
            <tr>
                <td><?php print htmlspecialchars($zip_data[$i]['Zipcode'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php print htmlspecialchars($zip_data[$i]['Prefectures'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php print htmlspecialchars($zip_data[$i]['Municipalities'], ENT_QUOTES, 'UTF-8'); ?></td>
                <td><?php print htmlspecialchars($zip_data[$i]['Area'], ENT_QUOTES, 'UTF-8'); ?></td>
            </tr>
<?php
    }
    //}
?>
        </table>
<?php
/* 
HINT ページ遷移について その５
検索条件をそのまま渡したいときに便利な関数をご紹介。
https://secure.php.net/manual/ja/function.http-build-query.php

//今回の例として、前を表示で使うと下記のようになる。
<?php
$param = $_GET;
$param['page'] = $page - 1;
$param = http_build_query($param, '', '&amp;');
?>
        <a href="http://codecamp6475.lesson6.codecamp.jp/PHP17/practice_post_code_advanced.php?<?php print $param ?>">前を表示</a>

*/


        if ($page != 1) {
?>
        <a href="http://codecamp6475.lesson6.codecamp.jp/PHP17/practice_post_code_advanced.php?pref=<?php print htmlspecialchars($_GET['pref'], ENT_QUOTES, 'UTF-8'); ?>&address=<?php print htmlspecialchars($address, ENT_QUOTES, 'UTF-8'); ?>&search_method=<?php print htmlspecialchars($_GET['search_method'], ENT_QUOTES, 'UTF-8'); ?>&page=<?php print htmlspecialchars($page-1, ENT_QUOTES, 'UTF-8'); ?>">前を表示</a>
<?php
        }
        if ($page != $allPage) {
?>
        <a href="http://codecamp6475.lesson6.codecamp.jp/PHP17/practice_post_code_advanced.php?pref=<?php print htmlspecialchars($_GET['pref'], ENT_QUOTES, 'UTF-8'); ?>&address=<?php print htmlspecialchars($address, ENT_QUOTES, 'UTF-8'); ?>&search_method=<?php print htmlspecialchars($_GET['search_method'], ENT_QUOTES, 'UTF-8'); ?>&page=<?php print htmlspecialchars($page+1, ENT_QUOTES, 'UTF-8'); ?>">次を表示</a>
<?php
        }
    }
?>
    </section>
</body>
</html>