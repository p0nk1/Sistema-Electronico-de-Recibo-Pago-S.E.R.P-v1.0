<?php
include_once "conexion_postgre.php";


/* Consultar usuario, email  */ 

function usuario_email ($usuario){
	
	$conn = bd_conectar();//LLamado de la funcion que se encarga de conectar 
	if (!$conn) 
	return -2;	

	$sql = "SELECT usuario FROM sss_usuarios_rp WHERE usuario='$usuario' ";		
	//echo $sql; exit;
	$r = bd_ejecutar_DQL($conn,$sql);//Llamado a funcion de ejecucion de sentencias de Insercion,Modificacion y Consulta 
	bd_desconectar($conn); 
	return $r; 
}


 ?>