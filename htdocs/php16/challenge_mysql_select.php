<?php
 
$emp_data = array();
 
//$order = '\'*\''; //初期値は全員
    //全件表示の場合は、WHERE以下をif文で追加する形が一般的

$order = '';
 
if (isset($_GET['job']) === TRUE) {
     $order = $_GET['job'];
}
 
$host     = 'localhost'; // データベースのホスト名又はIPアドレス ※CodeCampでは「localhost」で接続できます
$username = 'codecamp6475';  // MySQLのユーザ名
$passwd   = 'PCTZVCRT';    // MySQLのパスワード
$dbname   = 'codecamp6475';    // データベース名
 
$link = mysqli_connect($host, $username, $passwd, $dbname);
 
// 接続成功した場合
if ($link) {
 
    // 文字化け防止
    mysqli_set_charset($link, 'utf8');
    //全件表示の場合は、WHERE以下をif文で追加する形が一般的
    $query = 'SELECT emp_id, emp_name, job, age FROM emp_table WHERE job = \'' .$order .'\' ORDER BY emp_id ASC';
    echo $query;
 
    // クエリを実行します
    $result = mysqli_query($link, $query);
    
    // 1行ずつ結果を配列で取得します
    while ($row = mysqli_fetch_array($result)) {
        $emp_data[] = $row;
    }
 
    // 結果セットを開放します
    mysqli_free_result($result);
 
    // 接続を閉じます
    mysqli_close($link);
 
// 接続失敗した場合
} else {
    print 'DB接続失敗';
}

if ($err_msg != '') {
?>
<SCRIPT language="JavaScript">
　　alert('<?php echo $err_msg; ?>');
</SCRIPT>
<?php
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>課題1</title>
    <style type="text/css">
        table, td, th {
            border: solid black 1px;
        }
        table {
            width: 200px;
        }
    </style>
</head>
<body>
    </table>
        <p>表示する職種を選択してください。</p>
    <form>
        <select name="job">
            <option value="'*'" <?php if ($order === 'all') {print 'selected';} ?>>全員</option>
            <option value="manager" <?php if ($order === 'manager') {print 'selected';} ?>>マネージャー</option>
            <option value="analyst" <?php if ($order === 'analyst') {print 'selected';} ?>>アナリスト</option>
            <option value="clerk" <?php if ($order === 'clerk') {print 'selected';} ?>>一般職</option>
            <option value="parttime" <?php if ($order === 'parttime') {print 'selected';} ?>>アルバイト</option>
        </select>
        <input type="submit" value="表示">
    </form>
    <table>
    <caption>社員一覧</caption>
        <tr>
            <th>社員番号</th>
            <th>名前</th>
            <th>職種</th>
            <th>年齢</th>
        </tr>
<?php
if (empty ($emp_data) === FALSE) {
foreach ($emp_data as $value) {
?>
        <tr>
            <td><?php print htmlspecialchars($value['emp_id'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php print htmlspecialchars($value['emp_name'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php print htmlspecialchars($value['job'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php print htmlspecialchars($value['age'], ENT_QUOTES, 'UTF-8'); ?></td>
        </tr>
<?php
}
} else {
?>
        <tr>
            <td>何もヒットしませんでした</td>
        </tr>
<?php
}
?>
    </table>
</body>
</html>