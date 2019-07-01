<?php
include_once "../../funciones1.php";
    session_start();
    if(isset ($_SESSION["usuario"])){
    $usuario= $_SESSION["usuario"];
    }
$clave=$_GET['clave'];
$buscar=iniciar_sesionPG($usuario,$clave);
//print_r($buscar);
if(isset($buscar[0])) {

    echo 'true';
}
else {
    echo 'false';
}
?>
