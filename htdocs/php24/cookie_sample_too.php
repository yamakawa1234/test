<?php
 
// Cookieの仕組み理解を優先しているため、Modelへ処理を分離していません
//require_once '../include/conf/const.php';
//require_once '../include/model/function.php';
 
if (isset($_COOKIE['cookie_check']) === TRUE) {
    $cookie_check = 'checked';
} else {
    $cookie_check = '';
}
 
if (isset($_COOKIE['user_name']) === TRUE) {
    $user_name = $_COOKIE['user_name'];
} else {
    $user_name = '';
}
 
$cookie_check = htmlspecialchars($cookie_check, ENT_QUOTES, 'UTF-8');
$user_name    = htmlspecialchars($user_name  , ENT_QUOTES, 'UTF-8');
 
include_once '../../include/view/cookie_sample_top.php';
