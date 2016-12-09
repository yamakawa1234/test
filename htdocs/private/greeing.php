<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-wquiv="Content-Type" content="text/html; charset=utf-8">
	<title>挨拶</title>
</head>
<body>
	<?php $hour = date('H'); // 現在の時を$hour変数に格納 ?>

	<?php if (5 <= $hour && $hour < 10){ // 5時から10時までの間 ?>
	<p>おはようございます.</p>
	<?php }elseif (10 <= $hour && $hour < 18){ // 10時から18時までの間 ?>
	<p>こんにちは</p>
	<?php }else{ // それ以外 ?>
	<p>こんばんは</p>
	<?php } ?>
	<p>現在<?php echo $hour; ?></p>
</body>
</html>
<?php error_reporting(-1); ?>
