<?php
 $desde=$_GET['fe_desde'];
$actual =  date('d/m/Y');
    if ($desde == $actual || $desde> $actual ) {
        echo 'true';
    }
    else {
        echo 'false';
    }


?>
