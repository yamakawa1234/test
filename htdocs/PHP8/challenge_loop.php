<?php
$loop = 100;
$display  = '';
$stri = '';
for ($i = 1; $i <= $loop; $i++) {
    $display  = '';
    $fizz = gmp_div_r($i , 3);
    $buzz = gmp_div_r($i , 5);
    switch (true) {
        case ($fizz === 0) && ($buzz === 0):
            $display = 'FizzBuzz';
            break;
        case ($fizz === 0):
            $display = 'Fizz';
            break;
        case ($buzz === 0):
            $display = 'Buzz';
            break;
        default:
            $stri = (string)$i;
            $display = $stri;
    }
?>
    <p><?php print $display; ?></p>
<?php
}
?>