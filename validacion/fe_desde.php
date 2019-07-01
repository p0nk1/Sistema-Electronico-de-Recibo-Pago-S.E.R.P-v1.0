<?php

$desde=$_GET['desde'];
session_start();
$_SESSION["fe_desde"]= $desde;
echo $_SESSION["fe_desde"];
//echo 'true';
?>
