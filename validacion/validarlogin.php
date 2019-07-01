<?php
include_once "../../funciones1.php";
$login=$_GET['usuario'];
//$buscar=valida_login($login);
//if(isset($buscar[0])) {
    session_start();
    $_SESSION["usuario"]=$login;
    echo 'true';
//}
//else {
   // echo 'false';
//}
?>
