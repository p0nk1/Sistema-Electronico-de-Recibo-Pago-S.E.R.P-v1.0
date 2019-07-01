<?php
include_once "../bdd/CLAD.php";
$usuario=$_GET['CI_USUARIO'];
$buscar=consulta_usuario_det($usuario);
if(isset($buscar[0])) {
    echo 'false';
}
else {
    echo 'true';
}
?>
