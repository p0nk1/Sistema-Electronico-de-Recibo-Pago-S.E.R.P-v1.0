<?php
session_start();

if(isset($_POST['acceder']))
{

//include 'database.php';
$conn = pg_connect("host=192.168.0.90 dbname=db_saberytrabajo_2018 port=5432 user=sametsis password=+s4b1dur14+");
//echo $conn;
if($conn)
    {
	    //$usuario=strtoupper($_POST['usuario']);
	    $usuario=($_POST['usuario']);
	    $contrasena=md5($_POST['contrasena']);

	    $sql="SELECT cedula,usuario FROM sss_usuarios_rp where usuario='$usuario' and contrasena='$contrasena'";
		//echo $sql; exit;
            $result = pg_query($conn, $sql);

		        if($row = pg_fetch_array($result))
			{
			    $_SESSION['cedula_rp']=$row['cedula'];
			    $_SESSION['usuario_rp']=$row['usuario'];
			    $_SESSION['bienvenido']=1;
				echo "<meta http-equiv='refresh' content='0;URL=sno/sigesp_snorh_r_recibopago.php'/>"; //?cedula_rp=$cedula_rp
				//echo "<script>alert(\"Bienvenido, usuario ".$_SESSION['usuario_rp']."\");</script>";
			 }
			 else
 			 {

			    echo "<script>alert(\"El nombre de usuario o la contrase�a son incorrectos\");</script>";
			 }
    }
}
else
    {
     session_unset();
     session_destroy();
    }
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
<html>
<head>
<title>S.E.R.P v1.0</title>
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
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<style type="text/css">

.Estilo2 {
	font-size: 10px;
	font-weight: bold;
}
.bg-vinotinto{
  background:#006fba;
}
</style>
</head>
<body  background="img/" link="#006699" vlink="#006699" alink="#006699">
<a name="top"></a>
<!--1<table width="770" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">1-->
  <!--<tr>
    <td height="30" align="right">
	<img align="center" src="img/banda.jpg" width="770" height="55"></td>
  </tr> -->
  <!--2<tr>
    <td height="30" align="right">2--> <!-- class="cd-logo"-->
	<!--3<img align="center" src="img/banner_gmsyt.jpg" width="770" height="130"></td>
  </tr>
  <tr>
    <td height="20"  colspan="2" class="cd-menu">
	<table width="770" border="0" align="center" cellpadding="0" cellspacing="0">
		<tr>&nbsp;</tr>
          <td width="423" height="20" align="left" bgcolor="#E7E7E7" class="descripcion_sistema Estilo1">Emisi&oacute;n de Recibos de Pagos</td>
      </table></td>
  </tr>
 <tr>3-->
    <!--<td height="20" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>-->
  <!--4</tr>4-->
  <!--<tr>
    <td height="13" bgcolor="#FFFFFF" class="toolbar">&nbsp;</td>
  </tr>-->
  <!--5<tr>  </tr>
</table>5-->
<div class="container">
  <div class="row mb-5">
  <div class="col-md-12">
    <img src="img/banner_gmsyt.gif" style="width:100%; height:150px;">
  </div>
</div>

<div class="row">
<div class="col-md-4">
</div>
<div class="col-md-4 mt-5">
<form name="form1" method="post" action="">

<!--tabla 1 inicio-->

<!--6<table width="770"  border="0" align="center" cellpadding="0" cellspacing="0" class="contorno" >
      <tr class="titulo-celdanew-rojo" >
         <td height="22" colspan="3" class="titulo-celunes-roja" >&nbsp;</td>
		 <td height="22" colspan="4">6-->
          <input name="txtfechaingreso" type="hidden" class="sin-borde" id="txtfechaingreso" style="text-align:        center" value="<?PHP print date("Y/n/j")." - ".date("h:i:s");?>" size="12" maxlength="12" readonly>

     <!--7</td>

</tr>


    <td align="center" valign="top" bgcolor="#FFFFFF">7-->

<!--<form id="form1" name="form1" method="post" action="">-->

      <!--8<p align="left" class="Estilo2">&nbsp;</p>

            <p>&nbsp;</p>
            <table border="0" align="center" cellpadding="1" cellspacing="2" >
	      <tr>

	        </tr>
			  <tr>
			    <th width="90" rowspan="2" valign="top"  ><img src="img/login.png" ></th>
			    <td class='tabla'><div align="center" class="celdas-rojas"><span style="color:#FFFFFF">Usuario<span></div></td>
				 <td>8-->
<div class="form-group row">
<label for="usuario" class="col-sm-5 col-form-label bg-primary text-white"><i class="fas fa-user"></i> Usuario</label>
<div class="col-sm-7">
<input name="usuario" type="text" id="usuario" class="form-control" />
</div>
</div>
<!--9</td>
        </tr>
			  <tr>
			    <td width="90" class='tabla'><div align="center" class="celdas-rojas"><span style="color:#FFFFFF">Contrase&ntilde;a</span></div></td>
				 <td>9-->
<div class="form-group row">
<label for="contrasena" class="col-sm-5 col-form-label bg-primary text-white"><i class="fas fa-lock"></i> Contraseña</label>
<div class="col-sm-7">
<input name="contrasena" class="form-control" type="password"/>
</div>
</div>
<!--10</td>
			  </tr>
              <tr>

                <td colspan="3" align="center">10-->
<div class="form-group row" style="text-align:center;">
<input type="submit" name="acceder" class="btn bg-primary col-12 text-white mb-2" value="Entrar" />
<a href="registro_usuarios.php" class="col-12">Registrar Usuario</a>
<a href="recuperar_contrasena.php" class="col-12">¿Olvido su Contraseña?</a>
</div>
                <!--11</td>
			  </tr>

	  </table>11
      <p align="center" class="Estilo2"><a href="registro_usuarios.php">REGISTRESE COMO USUARIO AQUI</a></p>
      <p align="center" class="Estilo2"><a href="recuperar_contrasena.php">�Olvido su contrase&ntilde;a? </a></p>
	  <p>&nbsp;</p>
	  <p>&nbsp;</p>
	  <p>&nbsp;</p>11-->

<!--</form>-->


	<!--12</td>
 <td></td>

</table>12-->
<?php /*include ("piepagina.php"); */?>
  <!--13<p>&nbsp;</p>
  <p>&nbsp;</p>
  <p>&nbsp;</p>13-->

</form>
</div>
<div class="col-md-4">

</div>
</div>
</div>
</body>
</html>
