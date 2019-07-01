<?php
header('Content-Type: text/html; charset=ISO-8859-1');
session_start();
require_once("class_folder/class_funciones.php");

  $ls_operacion    = $_REQUEST["operacion"];
  $ls_usuario      = $_SESSION["txtusuario"];
  $ls_contrasena   = $_REQUEST["txtcontrasena"];
  $ls_vericontra   = $_REQUEST["txtvericontra"];

	/*if (($ls_usuario=="") or ($ls_usuario==NULL) or (empty($ls_usuario)))
           {
           echo '<script language="JavaScript">alert("No ha iniciado sessi�n para esta pantalla !");</script>';
           echo "<meta http-equiv='refresh' content='0;URL=index.php'/>";
	   }*/

switch ($_POST['operacion'])
    {


case "ACTUALIZAR":

include 'database.php';
$conn = pg_connect("user=".DB_USER." password=".DB_PASS." port=".DB_PORT." dbname=".DB_NAME." host=".DB_HOST);



		     if ($ls_contrasena==$ls_vericontra)
                        {
		        $ls_contrasena= md5($ls_contrasena);

		        $sql = "UPDATE sss_usuarios_rp SET contrasena='$ls_contrasena' where usuario='$ls_usuario'";
		        $sql = pg_query($conn, $sql);

		        echo '<script language="JavaScript">alert("La contrasena ha sido modificada correctamente!");</script>';
		        echo "<meta http-equiv='refresh' content='0;URL=index.php'/>";
			}
		     else
			{
		        echo '<script language="JavaScript">alert("La Verificacion de la contrasena no coincide!");</script>';
			}

break;


}    // del switch


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

.bg-vinotinto{
  background:#006fba;
}
</style>
</head>
<body background="img/" link="#006699" vlink="#006699" alink="#006699">
<a name="top"></a>
<!--1<table width="770" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">1-->
  <!--<tr>
    <td height="30" align="right">
	<img align="center" src="img/banda.jpg" width="770" height="55"></td>
  </tr>-->
  <!--2<tr>2-->
    <!--<td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>-->
    <!--3<td height="30" align="right">3--> <!-- class="cd-logo"-->
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
  <div class="col-md-3">
  </div>
  <div class="col-md-6 mt-5">

<!--<p>&nbsp;</p>-->
<form name="form1" method="post" action="">


<!--tabla 1 inicio-->

<!--7<table width="770"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC" class="contorno">
  <tr class="titulo-celdanew">
    <td height="22" colspan="5" class="titulo-celunes-roja">Recuperar Contrase�a</td>
  </tr>
  <tr class="formato-blanco">7-->
    <!--<td height="22" colspan="5"><p align="left" class="Estilo2 Estilo1"><a href="index.php"><img title="ATRAS" src="img/atras.jpg" alt="Grabar" width="30" height="30" border="0"></a></p></td>-->
  <!--8</tr>8-->
  <!-- <tr class="formato-blanco">
            <td height="22">&nbsp;</td>
            <td width="533" height="22"><div align="right"><strong>Fecha de Registro</strong>
              <label>
              <input name="txtfecreg" type="text" class="sin-borde" id="txtfecreg" style="text-align:center" value="<PHP print date("Y-m-d");?>" size="12" maxlength="12" readonly> <!-- readonly-->
  <!--$fecha_registro=date ("Y-m-d H:i:s");-->
  <!--</label>
</div></td>-->
  <!--9<td width="320"><p>&nbsp;</p>
    <p>&nbsp;</p></td>-->

  <!--<tr class="formato-blanco">
    <td width="320" height="22" align="center" colspan="4" ><span class="sin-borde"><strong><?php print $ls_pregunta; ?></strong></span></td>
  </tr>

    <tr class="formato-blanco">
      <td width="320" height="22" align="right"><span class="sin-borde"><strong></strong></span> Respuesta</td>
      <td width="448" height="22"><input  name="txtrespuesta" type="text" id="txtrespuesta" value="<?php print $ls_respuesta ?>" size="20" maxlength="20" style="text-align:center" onKeyPress="return keyRestrict(event,'abcdefghijklmn�opqrstuvwxyz ');">-->
        <!--<span class="sin-borde Estilo3"><strong></strong></span>
    <tr class="formato-blanco">
        <td height="22" align="right"><span class="sin-borde"><strong></strong></span>Nueva Contrase�a</td>
        <td height="22" colspan="4">10-->
        <div class="form-group row">
        <label for="txtcontrasena" class="col-sm-5 col-form-label" style="text-align:right;">Nueva Contrase&ntilde;a</label>
        <div class="col-sm-7">
        <input name="txtcontrasena" class="form-control" type="password" id="txtcontrasena" size="20" maxlength="20" onKeyPress="return keyRestrict(event,'abcdefghijklmn�opqrstuvwxyz '+'1234567890');" value="<?php print $ls_contrasena ?>"></td>
        </div>
        </div>
    <!--11</tr>
    <tr class="formato-blanco">
        <td height="22" align="right"><span class="sin-borde"><strong></strong></span>Verificar Contrase�a</td>
        <td height="22" colspan="4">11-->
        <div class="form-group row">
        <label for="txtvericontra" class="col-sm-5 col-form-label" style="text-align:right;">Verificar Contrase&ntilde;a</label>
        <div class="col-sm-7">
        <input name="txtvericontra" class="form-control" type="password" id="txtvericontra" size="20" maxlength="20" onKeyPress="return keyRestrict(event,'abcdefghijklmn�opqrstuvwxyz '+'1234567890');" value="<?php print $ls_vericontra ?>"></td>
        </div>
        </div>
    <!--12</tr>12-->

  <!--<tr>
	<td height="22" colspan="5"><div align="right"><a href="javascript: ue_search();"><img src="iconos/buscar.ico" alt="Buscar" width="20" height="20" border="0">Buscar</a></div></td>
	</tr>-->
  <input name="operacion" type="hidden" id="operacion">
  <!--13<td width="320"><p>&nbsp;</p>

    <p>&nbsp;</p></td>
  <tr class="formato-blanco">
    <td height="22" colspan="5"><div align="center">
    13-->
    <div class="form-group row">
      <div class="col-4">
      </div>
    <input name="submit" class="btn col-8 bg-vinotinto text-white" type="submit" id="txthabdes" value="Actualizar Contrase&ntilde;a" onClick="actualizar()">
    </div>
    <!--14</div></td>
  </tr>

  <td width="320"><p>&nbsp;</p>
    <p>&nbsp;</p></td>
  </tr>
</table>14-->
<!--<p>&nbsp;</p>-->
<!--tabla 2 fin-->



<?php /*include ("piepagina.php"); */?>
  <!--15<p>&nbsp;</p>
  <p>&nbsp;</p>15-->
</form>
</div>
<div class="col-md-3">

</div>
</div>
</div>
</body>

<script language="javascript">
f = document.form1;

function nuevo()
{
                        f.txtdescripcion.value="";
		        f.action="registro_usuarios.php";
			f.submit();
}

function actualizar()
{

       with (document.form1)
              {
			 /*if (valida_null(txtrespuesta,"Dede colocar una respuesta a su pregunta secreta !")==false)
				  {
				  txtrespuesta.focus();
				  }
			  else
				  {*/
				   if (valida_null(txtcontrasena,"Dede colocar una nueva contrasena !")==false)
					  {
					  txtcontrasena.focus();
					  }
				  else
					  {
					   if (valida_null(txtvericontra,"Dede colocar la verficacion de la contrasena !")==false)
						  {
						  txtvericontra.focus();
						  }
					  else
						  {
							f.operacion.value="ACTUALIZAR";
							f.action="cambio_contrasena.php";
							f.submit();
						  }
					  }
				  //}
              }

}//fin funcion


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

function eliminar()
{

		if (f.txtcodsituacion.value=="")
		{
		   alert("No ha seleccionado ning�n registro para eliminar !!!");
		}
		else
		{
			borrar=confirm("� Esta seguro de eliminar este registro ?");
			if (borrar==true)
			   {
				  f.operacion.value="ELIMINAR";
				  f.action="sisgeper_reg_situacion.php";
				  f.submit();
			   }
			else
			   {
				  alert("Eliminaci�n Cancelada !!!");
			   }
		 }

}

function buscar()
{
		f.operacion.value="";
		pagina="sisgeper_cat_situaciones.php";
		window.open(pagina,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=550,height=400,resizable=yes,location=no");
}

function rellenar_cad(cadena,longitud,campo)
{
	if (cadena!="")
	{
		var mystring=new String(cadena);
		cadena_ceros="";
		lencad=mystring.length;

		total=longitud-lencad;
		for(i=1;i<=total;i++)
		{
			cadena_ceros=cadena_ceros+"0";
		}
		cadena=cadena_ceros+cadena;
		if(campo=="doc")
		{
			document.form1.txtdocumento.value=cadena;
		}
		if(campo=="ced")
		{
			document.form1.txtcedula.value=cadena;
		}
		if(campo=="cmp")
		{
			document.form1.txtcomprobante.value=cadena;
		}
		if(campo=="cod")
		{
			document.form1.txtcodigo.value=cadena;
		}
		if(campo=="chequera")
		{
			document.form1.txtchequera.value=cadena;
		}
		if(campo=="numcheque")
		{
			document.form1.txtnumcheque.value=cadena;
		}
		if(campo=="desde")
		{
			document.form1.txtdesde.value=cadena;
		}
		if(campo=="hasta")
		{
			document.form1.txthasta.value=cadena;
		}
		if(campo=="voucher")
		{
			document.form1.txtchevau.value=cadena;
		}
	}
}

</script>
</html>
