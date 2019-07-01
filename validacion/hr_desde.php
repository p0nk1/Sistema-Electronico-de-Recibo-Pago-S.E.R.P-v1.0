<?php

$desde=$_GET['desde'];
session_start();
$_SESSION["hr_desde"]= $desde;
echo $_SESSION["hr_desde"];
//echo 'true';
?>
