<?php
    // 自分の得意な言語で
    // Let's チャレンジ！！
    
    $i = 1; // インデックス
    $j = 0;
    $tmp_array = array();
    
    $pram_table = array();
    $input_table = array();
    $result_table = array();
    
    //while (($tmp = fgets(STDIN)) !== FALSE) {
    $fp = fopen('./C030_test1.txt', 'r');
    //$fp = fopen('./C030_test2.txt', 'r');
    //$fp = fopen('./0byte.txt', 'r');
    while (($tmp = fgets($fp)) !== FALSE) {
            $tmp_array = explode(" ", $tmp);
            switch ($i){
                // 1行目
                case 1:
                    $pram_table = $tmp_array;
                    break;
                // 2行目以降
                default:
                    $input_table[$j] = $tmp_array;
                    $j++;
            }
            $i++;
    }
    //var_dump($pram_table);
    //var_dump($input_table);
    
    foreach ($input_table as $key => $value) {
        /*
        if (count($value[0]) === 1) {
            if ($value[0] >= 128){
                $wk_num = 1;
            } else {
                $wk_num = 0;
            }
            //print 'key'.$key;
            if ($key === ($pram_table[1] - 1)){
                if (empty($result_table[$key])){
                    $result_table[$key] = $wk_num;
                } else {
                    $result_table[$key] .= $wk_num;
                }
                $result_table[$key] .= $wk_num;
            } else {
                if (empty($result_table[$key])){
                    $result_table[$key] = $wk_num . ' ';
                } else {
                    $result_table[$key] .= $wk_num . ' ';
                }
            }
        } else {
        */
            foreach ($value as $key2 => $value2) {
                if ($value2 >= 128){
                    $wk_num = 1;
                } else {
                    $wk_num = 0;
                }
                //print 'key2:' .$key2 .'</br>';
                //print '$pram_table[1] - 1:' .($pram_table[1] - 1) .'</br>';
                if ($key2 === ($pram_table[1] - 1)){
                    if (empty($result_table[$key])){
                        $result_table[$key] = $wk_num;
                    } else {
                        $result_table[$key] .= $wk_num;
                    }
                } else {
                    if (empty($result_table[$key])){
                        $result_table[$key] = $wk_num . ' ';
                    } else {
                        $result_table[$key] .= $wk_num . ' ';
                    }
                }
                //print $result_table[$key];
            }
        /*
        }
        */
    }
    //var_dump($result_table);
    foreach ($result_table as $key => $value) {
        echo $value ."\n";
    }
    /*
    for ($i = 0; $i <= $pram_table[2] - 1; $i++){
        echo $user_result_table[$i] ."\n";
    }
    */
    
?>