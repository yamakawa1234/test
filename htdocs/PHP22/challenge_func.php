<?php
 
// エラーメッセージ用配列
$err_msg = array();
 
// 初期化
$height = '';
$weight = '';
$bmi    = '';
 
// リクエストメソッド取得
$request_method = get_request_method();
 
// POSTの場合
if ($request_method === 'POST') {
 
    // POSTデータ取得
    $height = get_post_data('height');
    $weight = get_post_data('weight');
 
    // 身長の値が小数かチェック
    if (check_float($height) !== TRUE) {
        $err_msg[] = '身長は数値を入力してください';
    }
 
    // 体重の値が小数かチェック
    if (check_float($weight) !== TRUE) {
        $err_msg[] = '体重は数値を入力してください';
    }
 
    // エラーがない場合
    if (count($err_msg) === 0) {
        // BMI算出
        $bmi = calc_bmi($height, $weight);
    }
 
}
 
 
/**
* BMIを計算1
* @param mixed $height 身長
* @param mixed $weight 体重
* @return float BMI
*/
/////////////////////
// calc_bmi関数作成
/////////////////////
function calc_bmi($height, $weight) {
    $wkbmi = $weight / ($height * $height);
    $bmi   = round($wkbmi,2); // 少数第２位を四捨五入
    return $bmi;
}    
/**
* 値が正の整数又は小数か確認
* @param mixed $float 確認する値
* @return bool TUREorFALSE
*/
/////////////////////
// check_float関数作成
/////////////////////
function check_float($float) {
    if(!preg_match('/^[1-9]+(\.[0-9]*)?$/', $float)) {
        $bool = false;
    } else {
        $bool = true;
    }
    return $bool;
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
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>BMI計算</title>
</head>
<body>
    <h1>BMI計算</h1>
    <form method="post">
        身長: <input type="text" name="height" value="<?php print $height ?>">
        体重: <input type="text" name="weight" value="<?php print $weight ?>">
        <input type="submit" value="BMI計算">
    </form>
<?php if (count($err_msg) > 0) { ?>
<?php     foreach ($err_msg as $value) { ?>
    <p><?php print $value; ?></p>
<?php     } ?>
<?php } ?>
<?php if ($request_method === 'POST' && count($err_msg) ===0) { ?>
    <p>あなたのBMIは<?php print $bmi; ?>です</p>
<?php } ?>
</body>
</html>