<?php

include_once "../bdd/script_db.php";

$usuario=$_POST["txtusuario"];
//$clave=$_POST["clave"];

/*if(isset($usuario))

{*/
		$resultado=usuario_email($usuario);
		//echo $resultado;die;
		//print_r($resultado); exit;
		if($resultado[0]['usuario'])
		{//echo entro1;die;
			
			/*session_start();
			$_SESSION["usuario"]=$resultado[0]['usuario'];
			$_SESSION['nombres']=$resultado[0]['nombres'];
			$_SESSION['apellidos']=$resultado[0]['apellidos'];
			$_SESSION["iniciada"]=1;
			$_SESSION["acceso"]=$resultado[0]['perfil'];
			$_SESSION["cf"]=$resultado[0]['id_cf'];
			$_SESSION["direccion"]=$resultado[0]['id_direccion'];*/
	
			
			echo "<script>alert('Bienvenido(a).')
			window.location='../index.php';
			</script>
			";	
		
		}
		else
			{echo entro2;
			echo "<script>alert('No se encuentre registrado el nombre de usuario: $usuario.')
			window.location='../recuperar_contrasena.php';
			</script>
			";	
		
			}
	
	
/*}else
{
	echo "<script>alert('Debes ingresar el usuario')</script>";	
}*/

?>
