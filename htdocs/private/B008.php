<?php

// テスト処理
$fp = fopen('./B008_test1.txt', 'r');
//$fp = fopen('./B008_test2.txt', 'r');
//$fp = fopen('./B008_test3.txt', 'r');

// ヘッダー処理
$input_array = trim(fgets($fp));
//$input_array = trim(fgets(STDIN));

$temp = explode(" ",$input_array);

// データ処理
for($i=0;$i<$temp[1];$i++){
    
	$input_array = trim(fgets($fp));
	//$input_array = trim(fgets(STDIN));
	
	$data_temp = explode(" ",$input_array);
	for($j=0;$j<$temp[0];$j++){
		$live_array[$i][$j] = $data_temp[$j];
	}
}

// 利益
$benefit = 0;

foreach($live_array as $keys => $values){
	$count = 0;
	foreach($values as $key => $value){
		$count += $value;
	}
	// 利益が0の場合はライブを開催しない
	if($count > 0){
		$benefit += $count;
	}
}
echo $benefit;