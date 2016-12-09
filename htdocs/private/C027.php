<?php
    // 自分の得意な言語で
    // Let's チャレンジ！！
    
    $i = 1; // インデックス
    $tmp_array = array();
    
    $player_array = array();
    $result_array = array();
    
    // テスト処理
    //$fp = fopen('./C027_test1.txt', 'r');
    $fp = fopen('./C027_test2.txt', 'r');
    
    // パラメーター取得
    $parm_array = explode(" ", fgets($fp));
    //$parm_array = explode(" ", fgets(STDIN));
    $h = $parm_array[0];
    $w = $parm_array[1];
    $n = $parm_array[2];
    $i = 0;
    while ($i + 1 <= $n) {
        $player_array[$i] = 0;
        $i++;
    }
    
    // トランプ配置取得
    $i = 0;
    while (($i + 1) <= $h) {
        $trump_array[$i] = explode(" ", fgets($fp));
        //$trump_array[$i] = explode(" ", fgets(STDIN));
        $i++;
    }
    
    // ログレングス取得
    $log_length_array = explode(" ", fgets($fp));
    //$log_length_array = explode(" ", fgets(STDIN));
    $l = $log_length_array[0];
    
    // ログ取得
    $i = 0;
    while (($tmp = fgets($fp)) !== FALSE) {
    //while (($tmp = fgets(STDIN)) !== FALSE) {
        $log_array[$i] = explode(" ", $tmp);
        $i++;
    }
    
    //var_dump($player_array);
    //var_dump($parm_array);
    //var_dump($trump_array);
    //print $trump_array[1][0];
    //var_dump($log_length_array);
    //var_dump($log_array);
    //exit;
    
    $p_c = 0;
    foreach ($log_array as $key => $value) {
        $trun1 = $trump_array[$value[0] - 1][$value[1] - 1];
        $trun2 = $trump_array[$value[2] - 1][$value[3] - 1];
        $trun1 = str_replace(array("\r\n","\n","\r"),"",$trun1);
        $trun2 = str_replace(array("\r\n","\n","\r"),"",$trun2);
        if ($trun1 === $trun2) {
            $player_array[$p_c] = $player_array[$p_c] + 1;
        } else {
            if (($p_c + 1) >= $n) {
                $p_c = 0;
            } else {
                $p_c++;
            }
        }
    }
    //var_dump($player_array);

    foreach ($player_array as $value) {
        echo $value * 2 .PHP_EOL;;
    }
    
?>