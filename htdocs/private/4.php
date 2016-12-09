
<?php
$a = array(
  array('a'=>1, 'b'=>2),
  array('a'=>1, 'b'=>3),
  array('a'=>2, 'b'=>3),
);
print_r(array_unique($a, SORT_REGULAR));

print $_SERVER['HTTP_HOST'];