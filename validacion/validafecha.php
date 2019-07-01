<?php
session_start();
 $hasta=$_GET['fe_hasta'];

if(isset ($_SESSION["fe_desde"])) {

    $desde=$_SESSION["fe_desde"];

    $fd= explode("/",$desde);
    $desde =   $fd[2]."-".$fd[1]."-".$fd[0];
    $fd= explode("/",$hasta);
    $hasta =   $fd[2]."-".$fd[1]."-".$fd[0];

    
    if ($hasta >= $desde) {
        echo 'true';
    }
    else {
        echo 'false';
    }
}

?>
