<?php
/*$abc = array(
    32,77,98,9
    );*/
$abc = array(
    'アメリカ' => 'ワシントンDC',
    '日本' => '東京',
    '韓国' => 'ソウル',
    'タイ' => 'バンコク',
    'マレーシア' => 'クアラルンプール',
    );    
    //foreach($abc as $key => $result) {
    foreach($abc as $result) {
        //print 'キー名: '.$key;
        print '内容　: '.$result;
        print '<br>';
    }
?>