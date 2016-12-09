
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>課題</title>
    <style type="text/css">
    .clearfix:after {
        content: "."; 
        display: block; 
        height: 0; 
        font-size:0;	
        clear: both; 
        visibility:hidden;
    }
    .clearfix{
        display: inline-block;
    } 
    #header {
        width: 1000px;
    }
    #header p {
        border: solid 1px #000000;
        text-align: center;
        float: left;
        box-sizing: border-box;
        margin: 0px auto;
    }
    
    .table p {
        border: solid 1px #000000;
        text-align: center;
        float: left;
        box-sizing: border-box;
        margin: 0px auto;
    }
    body {
        width: 1000px;
        height: 1000px;
    }
    .yb {
        width: 80px;
        clear: both;
    }
    .ken {
        width: 70px;
    }
    .twon {
        width: 200px;
    }
    .area {
        width: 250px;
    }
    #a {
        border: solid 5px #000000;
        background-color: #ffff00;
        border-radius: 10px;
        font-size: 35px;
        margin: 10px auto;
        padding: 5px;
        text-align: center;
    }
    #roulette td {
        width: 60px;
        height: 60px;
        /*css3限定、widthにborderを含めない！*/
        /*box-sizing: border-box;*/
        border: solid 1px #000000;
        margin: 0px;
    }
    #wrapper #button {
        margin: 10px auto;
        text-align: center;
    }
    .roulette {
        background-color: #da70d6;
    }
    .comp {
        background-color: #ffc0cb;
    }
</style> 
</head>
<body>
    <p>以下にファイルから読み込んだ住所データを表示</p>
    <p>住所データ</p>
    <div id=header>
        <p class=yb>郵便番号</p>
        <p class=ken>都道府県</p>
        <p class=twon>市区町村</p>
        <p class=area>町域</p>
    </div>
    <div class="clearfix"></div>
    
<?php
//ロケール情報を設定する
setlocale(LC_ALL, 'ja_JP.UTF-8');
//filename取得
$data = file_get_contents("test.csv");
//文字コード変換、UTF8からsjisに変換
//$data = mb_convert_encoding($data, 'UTF-8', 'sjis-win');
$data = mb_convert_encoding($data, 'UTF-8', 'UTF-8');
//tempfile:自動削除される一時ファイルを作成
$temp = tmpfile();
//ヘッダーあるいはメタデータをストリームまたはファイルポインタから取得する
$meta = stream_get_meta_data($temp);
 
//ファイルに書き込む
fwrite($temp, $data);
//ファイルポインタを先頭に巻き戻します。
rewind($temp);
 
//SplFileObject::fgetcsv — ファイルから行を取り出し CSV フィールドとして処理する
$file = new SplFileObject($meta['uri']);
$file->setFlags(SplFileObject::READ_CSV);
 
$csv  = array();
$i = 0;
foreach($file as $key => $line) {
    $csv[] = $line;
    /*print $line[0];
    print $line[1];
    print $line[2];
    print $line[3];*/
    //print $key;
    //print $line[$key];
    //$i = $i + 1;
}
    //print $key;
    
    for ($i = 0; $i <= $key; $i++) {
?>
        <div class=table>
        <p class=yb><?php print $csv[$key][0]; ?></p>
        <p class=ken><?php print $csv[$key][1]; ?></p>
        <p class=twon><?php print $csv[$key][2]; ?></p>
        <p class=area><?php print $csv[$key][3]; ?></p>
        </div>
        <div class="clearfix"></div>
<?php 
    }
     /*print $csv[0][0];
     print $csv[0][1];
     print $csv[0][2];
     print $csv[0][3];
     print $csv[0][1];
     print $csv[1][1];*/
fclose($temp);
$file = null;
//[2][6][7][8]
//print $csv[1][6];
//var_dump($csv);
?>
</body>
</html>