<?php
 
if (isset($_COOKIE['visited']) === TRUE) {
    $count = $_COOKIE['visited'] + 1;
} else {
    $count = 1;
}
 
setcookie('visited', $count, time() + 60 * 60 * 24 * 365);
 
print $count . '回目の訪問です';
