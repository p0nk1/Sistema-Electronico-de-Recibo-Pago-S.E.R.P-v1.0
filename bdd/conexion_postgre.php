<?php
	/*En este archivo se encuentran todo el conjunto de funciones relacionadas
	  con el manejador de Base de Datos en este caso SQL, dichas funciones son
	  Funcion de Conexion, Funcion De DML y Funcion de DQL*/

	//Estas variables Permiten el acceso a la bd, es decir, permitn accesar al manejador 
	
	/*La funcion de conectar  es la responsable crear el hilo de conexion o identificador de conexion*/
	
function bd_conectar()
	{//echo entr666o;die;
			if (!$conn = pg_connect("host=".DB_HOST. "dbname=".DB_NAME. "port=".DB_PORT. "user=".DB_USER. "password=".DB_PASS)
			{
				$status="No pudo conectarse al servidor";
				echo $status;
				exit();
			}
			
			return $conn;
			
	}
			
	
	
	
	/*La funcion de desconectar  es la responsable destruir el hilo de conexion o identificador de conexion*/
	function bd_desconectar($conn){
		pg_close($conn);
	}
	/*La funcion de los DML se encarga de realizar las ejecuiones de las modificcacones en la BD(Insertar datos,
	Modificar Datos y eliminar datos)*/
	function bd_ejecutar_DML($conn, $consulta){
		$r = pg_query($conn,$consulta);         //devuelve true o false
		if ($r)
			return pg_affected_rows($r);     
		else
			return -1;
	}

	/*La funcion de los DQL se encarga de realizar las ejecuiones de las consultas de registros de la bd*/
	function bd_ejecutar_DQL($conn, $consulta){
		$r = pg_query($conn,$consulta);         //devuele true o false
		if ($r){   //si retorna data
			$filas = pg_num_rows($r);
			for ($i = 1; $i <= $filas; $i++)
				$arr[] = pg_fetch_array($r, NULL,  PGSQL_ASSOC);
			return $arr;
		}else
			return -1;
	}
	
	/*La funcion de los DQL1 se encarga de realizar las ejecucion de las consultas de registros unitarios de la bd*/
	function bd_ejecutar_DQL1($conn, $consulta){
		$r = pg_query($conn,$consulta);         //devuele true o false
		if ($r){   //si retorna data
			$fila = pg_fetch_row($r);
			return $fila;
		}else
			return -1;
	}
	
		/*La funcion de los DQL2 se encarga de realizar las ejecucion de las consultas de borrar*/
	function bd_ejecutar_DQL2($conn, $consulta){
		$r = pg_query($conn,$consulta);         //devuele true o false
	}
?>
