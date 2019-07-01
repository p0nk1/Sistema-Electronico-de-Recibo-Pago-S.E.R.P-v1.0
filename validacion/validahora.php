<?php
session_start();
 $hasta="10/10/2011 ".$_GET['hr_hasta'];
if(isset ($_SESSION["hr_desde"])) {
    $desde="10/10/2011 ".$_SESSION["hr_desde"];
    $hasta=date("d/m/Y H:i",strtotime( $hasta));
    $desde=date("d/m/Y H:i", strtotime($desde));
    if ($hasta <$desde) {
        echo 'false';
    }
    else {
        echo 'true';
    }
}

?>
