<?php
// sessionはserver上に保存されている
session_start();

$date = date('Y年m月d日 H時i分s秒');
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (($_POST['delete']) === 'delete') {
        // セッション変数を全て削除
        $_SESSION = array();
        $_SESSION['count'] = 1;
        
        print '初めてのアクセスです' ."<br>";
        print $date .'(現在時刻)' ."<br>";
    }
} else {
    if (isset($_SESSION['count']) === TRUE) {
        $_SESSION['count']++;
        print '合計' .$_SESSION['count'] .'回目のアクセスです' ."<br>";
    } else {
        $_SESSION['count'] = 1;
        print '初めてのアクセスです' ."<br>";
    }
    print $date .'(現在時刻)' ."<br>";
    if (isset($_SESSION['before_time']) === TRUE) {
        print $_SESSION['before_time'] .'(前回のアクセス日時)' ."<br>";
    }
}
$_SESSION['before_time'] = $date;

// ファイル読み込み
include_once '../../include/view/challenge_session.php';