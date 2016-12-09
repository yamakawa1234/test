<?php

$date = date('Y年m月d日 H時i分s秒');
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (($_POST['delete']) === 'delete') {
        setcookie('access', ' ', time() - 3600);
        setcookie('before_time', ' ', time() - 3600);
        print '初めてのアクセスです' ."<br>";
        print $date .'(現在時刻)' ."<br>";
        $count = 1;
    }
} else {
    if (isset($_COOKIE['access']) === TRUE) {
        $count = $_COOKIE['access'] + 1;
        print '合計' .$count .'回目のアクセスです' ."<br>";
    } else {
        $count = 1;
        print '初めてのアクセスです' ."<br>";
    }
    print $date .'(現在時刻)' ."<br>";
    if (isset($_COOKIE['before_time']) === TRUE) {
        print $_COOKIE['before_time'] .'(前回のアクセス日時)' ."<br>";
    }
}

setcookie('access', $count, time() + 60 * 60 * 24 * 365);
setcookie('before_time', $date, time() + 60 * 60 * 24 * 365);

// ファイル読み込み
include_once '../../include/view/ini_button.php';