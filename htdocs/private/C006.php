<?php
    // 自分の得意な言語で
    // Let's チャレンジ！！
    
    $i = 1; // インデックス
    $j = 0;
    $wk_cal = 0;
    $tmp_array = array();
    
    $pram_table = array();
    $c_table = array();
    $user_table = array();
    $user_result_table = array();
    
    //while (($tmp = fgets(STDIN)) !== FALSE) {
    $fp = fopen('./test.txt', 'r');
    while (($tmp = fgets($fp)) !== FALSE) {
            $tmp_array = explode(" ", $tmp);
            switch ($i){
                // 1行目
                case 1:
                    $pram_table = $tmp_array;
                    break;
                // 2行目
                case 2:
                    $c_table = $tmp_array;
                    break;
                // 3行目以降
                default:
                    $user_table[$j] = $tmp_array;
                    $j = $j + 1;
            }
            $i = $i + 1;
    }
    //var_dump($pram_table);
    //var_dump($c_table);
    //var_dump($user_table);
    foreach ($user_table as $key => $value) {
        foreach ($value as $key2 => $value2) {
            if ($key2 === $pram_table[0] - 1){
                $wk_cal = $wk_cal + $c_table[$key2] * $value2;
                $user_result_table[$key] = round($wk_cal);
            } else {
                $wk_cal = $wk_cal + $c_table[$key2] * $value2;
            }
        }
        $wk_cal = 0;
    }
    // var_dump($user_result_table);
    rsort($user_result_table,SORT_NUMERIC);
    for ($i = 0; $i <= $pram_table[2] - 1; $i++){
        echo $user_result_table[$i] ."\n";
    }
?>