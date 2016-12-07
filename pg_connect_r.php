<?php
ini_set( 'display_errors', 1 );

  if (!($cn = pg_connect("dbname=mre_softbank user=postgres"))) {
    echo "connect error\n";
  } else {
    echo "connect ok\n";
  }

?>
