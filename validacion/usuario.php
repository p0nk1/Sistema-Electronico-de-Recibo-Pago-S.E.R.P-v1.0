<?php

$login=$_GET['txtusuario'];

include 'database.php';
$conn = pg_connect("host=192.168.0.90 dbname=db_saberytrabajo_2018 port=5432 user=sametsis password=+s4b1dur14+");

$sql = "SELECT usuario FROM sss_usuarios_rp where usuario='$login'";
//echo $sql;
$r = pg_query($conn, $sql);

 if ($r){   //si retorna data
			$filas = pg_num_rows($r);
			for ($i = 1; $i <= $filas; $i++)
				$arr[] = pg_fetch_array($r, NULL,  PGSQL_ASSOC);
			$buscar=$arr;
		}else
			$buscar='false';
	
//echo "aqui: "
print_r ($buscar); 
//$buscar=valida_login($login);
if(isset($buscar[0])) {
    echo 'false';
}
else {
    echo 'true';
}
?>
