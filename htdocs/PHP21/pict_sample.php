<?php
// 画像ファイル転送
// $_FILESにアップロードされたファイルの情報が格納される！！！！
// 今回はファイル偽造等は無視する・・・
// 最初はファイルを任意の名前でアップロードすることを目標とする！

$p_name = 'test.jpeg';
if (!empty($_FILES)) {
    print_r($_FILES);
    // ファイルが存在するか？
    if (is_uploaded_file($_FILES["upfile"]["tmp_name"])) {
        // move_uploaded_file
        // 画像の移動＆名前の変更ができる関数
      //if (move_uploaded_file($_FILES["upfile"]["tmp_name"], "./" . $_FILES["upfile"]["name"])) {
      if (move_uploaded_file($_FILES["upfile"]["tmp_name"], "./" . $p_name)) {
          // アクセス権限付与
        chmod("files/" . $_FILES["upfile"]["name"], 0644);
        echo $_FILES["upfile"]["name"] . "をアップロードしました。";
      } else {
        echo "ファイルをアップロードできません。";
      }
    } else {
      echo "ファイルが選択されていません。";
    }
}

//画像ファイルの指定
$img_file = 'http://codecamp6475.lesson6.codecamp.jp//PHP21/1.\(jpg|jpeg|gif|png|bmp)/i\'';
//拡張子の取得
$file_info = pathinfo($img_file);
$img_extension = strtolower($file_info['extension']);
//出力
echo $img_extension;
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>sample</title>
</head>
<body>
<form method="post" enctype="multipart/form-data">
  ファイル：<br />
  <input type="file" name="upfile" size="30" /><br />
  <br />
  <input type="submit" value="アップロード" />
</form>
</body>
</html>