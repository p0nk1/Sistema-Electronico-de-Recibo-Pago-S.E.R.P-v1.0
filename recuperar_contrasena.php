<?php
header("Content-Type: text/html; charset=ISO-8859-1");

session_start();
require_once("class_folder/class_funciones.php");

  $ls_operacion    = $_REQUEST["operacion"];
  $ls_usuario      = $_REQUEST["txtusuario"];
  $ls_respusuario  = $_REQUEST["txtrespuesta"];
  $ls_respusuario  = strtolower ($ls_respusuario);
  $ls_respusuario  = md5($ls_respusuario);

switch ($_POST['operacion'])
    {


case "VERIFICAR":

//include 'database.php';
$conn = pg_connect("host=192.168.0.90 dbname=db_saberytrabajo_2018 port=5432 user=sametsis password=+s4b1dur14+");


      $sql = "SELECT usuario FROM sss_usuarios_rp where usuario='$ls_usuario'";
      $sql = pg_query($conn, $sql);


	   while ($row = pg_fetch_array($sql))
		{
		$ls_usureg=$row["usuario"];
		}

	        if  ($ls_usuario==$ls_usureg)
	            {

		    $boton=1;

		      $sql = "SELECT pregunta FROM sss_usuarios_rp where usuario='$ls_usuario'";
		      $sql = pg_query($conn, $sql);

			   while ($row = pg_fetch_array($sql))
				{
				$ls_pregunta=$row["pregunta"];
				}
		    }
		else
		    {
		    echo '<script language="JavaScript">alert("El usuario que introdujo no coincide con ninguno registrado, verifique!");</script>';
                    $ls_usuario="";
	            }


break;

case "ENVIAR":

//include 'database.php';
$conn = pg_connect("host=192.168.0.90 dbname=db_saberytrabajo_2018 port=5432 user=sametsis password=+s4b1dur14+");


      $sql = "SELECT respuesta FROM sss_usuarios_rp where usuario='$ls_usuario'";
      $sql = pg_query($conn, $sql);

	   while ($row = pg_fetch_array($sql))
		{
		$ls_respuesta=$row["respuesta"];
		}

	        if  ($ls_respuesta==$ls_respusuario)
	            {
                    //echo '<script language="JavaScript">alert("ENTRO");</script>';
		    echo "<meta http-equiv='refresh' content='0;URL=cambio_contrasena.php'/>";
                    $_SESSION['txtusuario']=$ls_usuario;
		    }
		else
		    {
		    echo '<script language="JavaScript">alert("La respuesta es incorrecta, verifique!");</script>';
                    $ls_usuario="";
	            }


break;

}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
<html>
<head>
<title>Recuperar Contrase&ntilde;a</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<!--<link href="css/rpc.css" rel="stylesheet" type="text/css">
<link href="css/cabecera.css" rel="stylesheet" type="text/css">
<link href="css/general.css" rel="stylesheet" type="text/css">
<link href="css/tablas.css" rel="stylesheet" type="text/css">
<link href="css/ventanas.css" rel="stylesheet" type="text/css">-->
<link rel="shortcut icon" href="img/logo_gmsyt.png" type="image/x-icon" >
<!--BOOTSTRAP / FONTAWESOME-->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<link rel="stylesheet" href="fontawesome-5.3.1/css/all.css">

<script type="fontawesome-5.3.1/js/all.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/valida_tecla.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/disabled_keys.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<!-- Copyright hkum, Inc. All rights reserved. -->
<style type="text/css">
.Estilo1 {font-size: 10px}
.Estilo2 {color: #CC3300}
.Estilo3 {color: #CC0000}

.bg-primary{
  background:#006fba;
}
</style>
</head>
<body background="img/" link="#006699" vlink="#006699" alink="#006699">
<a name="top"></a>
<!--1<table width="770" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">-->
  <!--<tr>
    <td height="30" align="right">
	<img align="center" src="img/banda.jpg" width="770" height="55"></td>
  </tr>-->
  <!--2<tr>2-->
    <!--<td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>-->
    <!--<td height="30" align="right">3--><!-- class="cd-logo"-->
	<!--4<img align="center" src="img/banner_gmsyt.jpg" width="770" height="130"></td>4-->
	<!--<td height="30"> <!-- class="cd-logo"-->
	<!--<img align="center" src="img/logo_sisgeper.jpg" width="440" height="130"></td>
    <td><img align="center" src="img/logo_bicentenario.jpg" width="95" height="125"></td>-->
  <!--5</tr>
  <tr>
    <td height="20"  colspan="2" class="cd-menu">5-->
	<!--<table width="770" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>&nbsp;</tr>
          <td width="423" height="20" align="left" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Emisi�n de Recibos de Pago</td>
      </table></td>-->
  <!--6</tr>
</table>6-->
<div class="container">
<div class="row mb-5">
<div class="col-md-12">
  <img src="img/banner_gmsyt.gif" style="width:100%; height:150px;">
</div>
</div>

<div class="row">
  <div class="col-md-4">
        <a href="index.php" style="font-size:40px;"><i class="fas fa-power-off text-primary "></i></a>
  </div>
  <div class="col-md-4 mt-5">

<!--<p>&nbsp;</p>-->
<form name="form1" method="post" action="">


<!--tabla 1 inicio-->

<!--7<table width="770"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC" class="contorno">
	<tr class="titulo-celdanew">
		<td height="22" colspan="4" class="titulo-celunes-roja">Recuperar Contrase�a</td>
	</tr>
	<tr class="formato-blanco">
		<td height="22" colspan="4">
			<p align="left" class="Estilo2 Estilo1">
				<a href="index.php">
					<img title="ATRAS" src="img/cerrar.jpg" alt="Grabar" width="45" height="45" border="0">
				</a>
			</p>
		</td>
	</tr>
	<td width="320"><p>&nbsp;</p>
	<p>&nbsp;</p></td>

	<tr class="formato-blanco">
		<td width="320" height="22" align="right">
			<span class="sin-borde"><strong> Usuario</strong></span>
		</td>
		<td width="448" height="22">
    7-->
    <div class="form-group row">
    <label for="txtusuario" class="col-sm-5 col-form-label" style="text-align:right;">Usuario</label>
    <div class="col-sm-7">
    <input  name="txtusuario" placeholder="Ejemplo: mperez" class="form-control" type="text" id="txtusuario" value="<?php print $ls_usuario ?>" size="20" maxlength="20" style="text-align:center" onKeyPress="return keyRestrict(event,'abcdefghijklmn�opqrstuvwxyz ');" <?php if ($boton==1) { echo "readonly"; } ?>>
    </div>
    </div>
      <!--8<span class="sin-borde Estilo3"><strong>   Ejemplo: mperez</strong></span>8-->

			<?php if ($boton==1) {  ?>
			<!--<tr class="formato-blanco">
	<td width="320" height="22" align="center" colspan="4" ><span class="sin-borde"><strong>--><!--</strong></span></td>
	</tr>

	<tr class="formato-blanco">
	<td width="320" height="22" align="right"><span class="sin-borde"><strong></strong></span> Respuesta</td>
	<td width="448" height="22">9-->
  <div class="form-group row">
    <div class="col-4">
    </div>
    <label for="txtusuario" class="col-8 col-form-label" style="text-align:center;"><?php echo "".$ls_pregunta."?"; ?></label>
  </div>
  <div class="form-group row">
    <div class="col-4">
    </div>
  <div class="col-sm-8">
  <input  name="txtrespuesta" class="form-control" type="text" id="txtrespuesta" value="<?php print $ls_respuesta ?>" size="20" maxlength="20" style="text-align:center" onKeyPress="return keyRestrict(event,'abcdefghijklmn�opqrstuvwxyz '+'1234567890 ');">
  </div>
  </div>
  <!--9<span class="sin-borde Estilo3"><strong></strong></span>9-->
	<?php } ?>
	<!--10</tr>10-->

	<input name="operacion" type="hidden" id="operacion">
	<!--11<td width="320"><p>&nbsp;</p>

	<p>&nbsp;</p></td>
	<tr class="formato-blanco">
	<td height="22" colspan="4"><div align="center">11-->
	<!--<input name="submit" type="submit" id="txthabdes" value="VERIFICAR USUARIO" onClick="verificar_usuario()">-->
  <div class="form-group row">
    <div class="col-3"></div>
  <input name="txtboton" class="btn col-9 bg-primary text-white" type="submit" id="txtboton" align="center" <?php if ($boton==1) { echo 'value="Enviar Respuesta"'; }else{ echo 'value="Verificar Usuario"'; }  ?> onClick="verificar_usuario()">
  </div>
  <!--12</div></td>
	</tr>

	<td width="320"><p>&nbsp;</p>
	<p>&nbsp;</p></td>
	</tr>
</table>12-->
<!--<p>&nbsp;</p>-->
<!--tabla 1 fin-->



<?php /*include ("piepagina.php");*/?>
  <!--12<p>&nbsp;</p>
  <p>&nbsp;</p>12-->
</form>
</div>
<div class="col-md-4">

</div>
</div>
</div>
</body>

<script language="javascript">
f = document.form1;



function verificar_usuario()
{

       with (document.form1)
              {
			   if (valida_null(txtusuario,"El usuario esta vacio !")==false)
				  {
				  txtusuario.focus();
				  }
			  else
				  {
					if (f.txtboton.value=="Verificar Usuario")
						{
							f.operacion.value="VERIFICAR";
							f.action="recuperar_contrasena.php";
							f.submit();
						}
					else
						{
							f.operacion.value="ENVIAR";
							f.action="recuperar_contrasena.php";
							f.submit();

						}
				  }
              }

}


function valida_null(field,mensaje)
{
  with (field)
  {
    if (value==null||value=="")
      {
        alert(mensaje);
        return false;
      }
    else
      {
         for ( i = 0; i < value.length; i++ ) {
                 if ( value.charAt(i) != " " ) {
                         return true
                 }
         }
		 alert(mensaje);
         return false
      }
  }
}


</script>
</html>
