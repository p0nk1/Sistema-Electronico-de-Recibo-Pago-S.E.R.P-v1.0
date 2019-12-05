<?php
header('Content-Type: text/html; charset=ISO-8859-1');
session_start();
require_once("class_folder/class_funciones.php");

  $ls_operacion    = $_REQUEST["operacion"];
  $ls_cedula       = $_REQUEST["txtcedula"];
  $ls_usuario      = $_REQUEST["txtusuario"];
  $ls_pregunta     = $_REQUEST["txtpregunta"];
  $ls_respuesta    = $_REQUEST["txtrespuesta"];
  $ls_contrasena   = $_REQUEST["txtcontrasena"];
  $ls_vericontra   = $_REQUEST["txtvericontra"];
  $ls_respuesta    = strtolower ($ls_respuesta);

switch ($_POST['operacion'])
    {


case "GUARDAR":

//include 'database.php';
$conn = pg_connect("host=192.168.0.90 dbname=db_saberytrabajo_2019 port=5432 user=sametsis password=+s4b1dur14+");


      $sql = "SELECT cedper FROM sno_personal where cedper='$ls_cedula'";
      $sql = pg_query($conn, $sql);


      //$sql=mysql_query($sql) or die("error en $error: ".mysql_error());


	   while ($row = pg_fetch_array($sql))
		{
		$ls_cedper=$row["cedper"];
		}

      $sql = "SELECT * FROM sss_usuarios_rp where cedula='$ls_cedula'";
      $sql = pg_query($conn, $sql);

      //$sql=mysql_query($sql) or die("error en $error: ".mysql_error());


	   while ($row = pg_fetch_array($sql))
		{
		$ls_busced=$row["cedula"];
		$ls_clavereal=$row["contrasena"];
		}

      $sql = "SELECT usuario FROM sss_usuarios_rp where usuario='$ls_usuario'";
      $sql = pg_query($conn, $sql);

      //$sql=mysql_query($sql) or die("error en $error: ".mysql_error());


	   while ($row = pg_fetch_array($sql))
		{
		$ls_usureg=$row["usuario"];
		}

         // echo " usuario ".$ls_usuario." cedula ".$ls_cedper;

       if  (($ls_cedper!="") or ($ls_cedper!=NULL))
	   {
	   if  (($ls_busced=="") or ($ls_busced==NULL) or (empty($ls_busced)))
	       {
	        if  ($ls_usuario!=$ls_usureg)
	            {
		     if ($ls_contrasena==$ls_vericontra)
                        {
			if (($ls_pregunta!="") and ($ls_respuesta!=""))
                           {
		             $ls_contrasena= md5($ls_contrasena);
		             $ls_respuesta= md5($ls_respuesta);

		             $sql = "INSERT INTO sss_usuarios_rp (cedula, usuario, contrasena, pregunta, respuesta) VALUES ('$ls_cedula','$ls_usuario', '$ls_contrasena','$ls_pregunta','$ls_respuesta')";
		             $sql = pg_query($conn, $sql) or die("Imposible registrar en BD..."." ".pg_last_error());

		             echo '<script language="JavaScript">alert("El Usuario ha sido registrado correctamente!");</script>';
		             echo "<meta http-equiv='refresh' content='0;URL=index.php'/>";
			    }
			else
			    {
		             echo '<script language="JavaScript">alert("Debe colocar una pregunta y una respuesta secreta!");</script>';
			    }
			}
		     else
			{
		        echo '<script language="JavaScript">alert("La Verificacion de la contrasena no coincide!");</script>';
			}
		     }
		 else
		     {
		     echo '<script language="JavaScript">alert("El Nombre de Usuario no esta disponible, Intente con otro!");</script>';
	             }
	        }
            else
	        {
	        echo '<script language="JavaScript">alert("Ya se ha creado un usuario para la cedula que introdujo, contacte con el administrador!");</script>';
                }
	   }
       else
	   {
	   echo '<script language="JavaScript">alert("La cedula que introdujo no pertenece a ningun personal registrado en nomina, verifique!");</script>';
           }


break;


}    // del switch


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
<html>
<head>
<title>Registro de Usuarios</title>
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
<script src="js/jquery-1.6.min.js" type="text/javascript"></script>
<script src="js/jquery.validate.js" type="text/javascript"></script>
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
<body background="img/fondo.jpg" link="#006699" vlink="#006699" alink="#006699">
<a name="top"></a>
<!--1<table width="770" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">1-->
  <!--<tr>
    <td height="30" align="right">
	<img align="center" src="img/banda.jpg" width="770" height="55"></td>
  </tr>-->
  <!--2<tr>2-->
    <!--<td height="30" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>-->
    <!--3<td height="30" align="right">3--><!-- class="cd-logo"-->
	<!--4<img align="center" src="img/banner_gmsyt.jpg" width="770" height="130"></td>4-->
	<!--<td height="30"> class="cd-logo"-->
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
  <img src="img/logo_gmsyt.png" style="width:150px; height:160px;">
</div>
</div>
<!--<p>&nbsp;</p>-->
<div class="row">
  <div class="col-md-3">
        <a href="index.php" style="font-size:40px;"><i class="fas fa-arrow-alt-circle-left text-primary"></i></a>
  </div>
  <div class="col-md-6 mt-5">
<form name="form1" method="post" action="">


<!--tabla 1 inicio-->

<!--7<table width="770"  border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC" class="contorno">
  <tr class="titulo-celdanew">
    <td height="22" colspan="4" class="titulo-celunes-roja">Registro de Usuarios</td>
  </tr>
  <tr class="formato-blanco">
    <td height="22" colspan="4"><p align="left" class="Estilo2 Estilo1">-->
<!--</p></td>
  </tr>-->
  <!-- <tr class="formato-blanco">
            <td height="22">&nbsp;</td>
            <td width="533" height="22"><div align="right"><strong>Fecha de Registro</strong>
              <label>
              <input name="txtfecreg" type="text" class="sin-borde" id="txtfecreg" style="text-align:center" value="<PHP print date("Y-m-d");?>" size="12" maxlength="12" readonly> <!-- readonly-->
  <!--$fecha_registro=date ("Y-m-d H:i:s");-->
  <!--</label>
</div></td>7-->
  <!--8<td width="320"><p>&nbsp;</p>
    <p>&nbsp;</p></td>

  <tr class="formato-blanco">
    <td width="320" height="22" align="right"><span class="sin-borde"><strong></strong></span> C&eacute;dula</td>
    <td width="448" height="22">8-->
    <div class="form-group row">
    <label for="txtcedula" class="col-sm-5 col-form-label" style="text-align:right;">Cedula</label>
    <div class="col-sm-4">
    <input name="txtcedula" class="form-control" type="text" id="txtcedula" style="text-align:center" onBlur="rellenar_cad(this.value,8,'ced')" value="<?php print $ls_cedula;?>" size="10" maxlength="8" onKeyPress="return keyRestrict(event,'1234567890');" <?php //print $ls_readOnly_doc; ?>></td></tr>
    </div>
    </div>
    <!--9<tr class="formato-blanco">
      <td width="320" height="22" align="right"><span class="sin-borde"><strong></strong></span> Usuario</td>
      <td width="448" height="22">9-->
      <div class="form-group row">
      <label for="txtusuario" class="col-sm-5 col-form-label" style="text-align:right;">Usuario</label>
      <div class="col-sm-6">
      <input  name="txtusuario" placeholder="Ejemplo:mperez" class="form-control" type="text" id="txtusuario" value="<?php print $ls_usuario ?>" size="20" maxlength="20" style="text-align:center" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz');">
      </div>
      </div>
        <!--10<span class="sin-borde Estilo3"><strong>   Ejemplo:  mperez</strong></span>
    <tr class="formato-blanco">
        <td height="22" align="right"><span class="sin-borde"><strong></strong></span>Contrase�a</td>
        <td height="22" colspan="4">10-->
        <div class="form-group row">
        <label for="txtcontrasena" class="col-sm-5 col-form-label" style="text-align:right;">Contrase&ntilde;a</label>
        <div class="col-sm-6">
        <input name="txtcontrasena" placeholder="Ejemplo:Mperez123" class="form-control" type="password" id="txtcontrasena" size="20" maxlength="20" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz'+'1234567890');" value="<?php print $ls_contrasena ?>">
        </div>
        </div>
<!--11<span class="sin-borde Estilo3"><strong>   Ejemplo:  Mperez123</strong></span></td>

    </tr>
    <tr class="formato-blanco">
        <td height="22" align="right"><span class="sin-borde"><strong></strong></span>Verificar Contrase�a</td>
        <td height="22" colspan="4">11-->
        <div class="form-group row">
        <label for="txtvericontra" class="col-sm-5 col-form-label" style="text-align:right;">Verificar Contrase&ntilde;a</label>
        <div class="col-sm-6">
        <input name="txtvericontra" placeholder="Ejemplo:Mperez123" class="form-control" type="password" id="txtvericontra" size="20" maxlength="20" onKeyPress="return keyRestrict(event,'abcdefghijklmnopqrstuvwxyz'+'1234567890');" value="<?php print $ls_vericontra ?>">
        </div>
        </div>
<!--12<span class="sin-borde Estilo3"><strong>   Nota: NO debe usar caracteres especiales</strong></span></td>
    <tr class="formato-blanco">
      <td width="320" height="22" align="right"><span class="sin-borde"><strong></strong></span> Pregunta Secreta</td>
      <td width="448" height="22">12-->
      <div class="form-group row">
      <label for="txtpregunta" class="col-sm-5 col-form-label" style="text-align:right;">Pregunta Secreta</label>
      <div class="col-sm-7">
      <input  name="txtpregunta" placeholder="Ejemplo:Cual es su color Favorito" class="form-control" type="text" id="txtpregunta" value="<?php print $ls_pregunta ?>" size="30" maxlength="30" style="text-align:center" onKeyPress="return keyRestrict(event,'abcdefghijklmn�opqrstuvwxyz '+'1234567890 ');">
      </div>
      </div>
        <!--13<span class="sin-borde Estilo3"><strong>   Ejemplo:  Cual es tu color favorito</strong></span>
    <tr class="formato-blanco">
      <td width="320" height="22" align="right"><span class="sin-borde"><strong></strong></span> Respuesta Secreta</td>
      <td width="448" height="22">13-->
      <div class="form-group row">
      <label for="txtrespuesta" class="col-sm-5 col-form-label" style="text-align:right;">Respuesta Secreta</label>
      <div class="col-sm-7">
      <input  name="txtrespuesta" placeholder="Ejemplo:Rojo" class="form-control" type="text" id="txtrespuesta" value="<?php print $ls_respuesta ?>" size="30" maxlength="30" style="text-align:center" onKeyPress="return keyRestrict(event,'abcdefghijklmn�opqrstuvwxyz '+'1234567890 ');">
      </div>
      </div>
        <!--14<span class="sin-borde Estilo3"><strong></strong></span>
    </tr>14-->

  <!--<tr>
	<td height="22" colspan="5"><div align="right"><a href="javascript: ue_search();"><img src="iconos/buscar.ico" alt="Buscar" width="20" height="20" border="0">Buscar</a></div></td>
	</tr>-->
  <input name="operacion" type="hidden" id="operacion">
  <!--15<td width="320"><p>&nbsp;</p>

    <p>&nbsp;</p></td>
  <tr class="formato-blanco">
    <td height="22" colspan="4"><div align="center">
    -->
    <div class="form-group row">
      <div class="col-4">
      </div>
    <input name="submit" class="col-8 btn bg-primary text-white" type="submit" id="txthabdes" value="Registrar Usuario" onClick="guardar()">
    </div>
    <!--
    </div></td>
  </tr>

  <td width="320"><p>&nbsp;</p>
    <p>&nbsp;</p></td>
  </tr>
</table>-->
<!--<p>&nbsp;</p>15-->
<!--tabla 2 fin-->



<?php /*include ("piepagina.php");*/ ?>
  <!--16<p>&nbsp;</p>
  <p>&nbsp;</p>16-->
</form>
</div>
<div class="col-md-3">

</div>
</div>
</div>
</body>

<script>
f = document.form1;

function nuevo()
{
                        f.txtdescripcion.value="";
		        f.action="registro_usuarios.php";
			f.submit();
}

function guardar()
{

       with (document.form1)
              {
			   if (valida_null(txtcedula,"La c�dula esta vacia !")==false)
				  {
				  txtcdula.focus();
				  }
			  else
				  {
							f.operacion.value="GUARDAR";
							f.action="registro_usuarios.php";
							f.submit();
				  }
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

/*function rellenar_cad(cadena,longitud,campo)
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
}*/




jQuery(document).ready(function(){
        jQuery.validator.addMethod("expresion",function(value,element,regexp){
            var reg = new RegExp(regexp);
            return this.optional(element) || reg.test(value);
        },"" );


        // binds form submission and fields to the validation engine
        jQuery("#Nuevo").validate({
            rules:{
				txtcedula:{
					required:true
				},

				txtusuario:{
					required:true
				},

                EMAIL_USUARIO:{
                    required:true,
					email:true
                },
                TLF_USUARIO:{
                    number:true,
					maxlength:12
                },
				CEL_USUARIO:{
                    required:true,
					number:true,
					maxlength:12
                },
                LOGIN:{
                    expresion:"^[aA-zZ0-9ñ�']{6,10}$",
                    required:true,
					remote:"validarlogin.php"

                },
                PASSWORD:{
                    expresion:"^[aA-zZ0-9ñ�-*/#_@$&[]}{']{8,10}$",
                    required:true
                },
                PASSWORD2:{
                    equalTo:"#PASSWORD",
                    required:true
                }
            },
            messages:{

				txtcedula:{
					required:"Campo Obligatorio"
				},

				txtusuario:{
					required:"Campo Obligatorio"
				},

               EMAIL_USUARIO:{
                    required:"Campo Obligatorio",
					email:"Email invaildo"
                },
                TLF_USUARIO:{
                    number:"Solo permite numeros",
					maxlength:"Permite un maximo 12 numeros"
                },
				CEL_USUARIO:{
                    required:"Campo Obligatorio",
					number:"Solo permite numeros",
					maxlength:"Permite un maximo 12 numeros"
                },
                LOGIN:{
                    expresion:"Solo permite alfanumericos , de seis(6) a diez(10) Caracteres",
                    required:"Campo Obligatorio",
					remote:"El Login ya se encuentra en el sistema"

                },
                PASSWORD:{
                    expresion:"Solo permite alfanumericos, de ocho(8) a diez(10) digitos",
                    required:"Campo Obligatorio"
                },
                PASSWORD2:{
                    equalTo:"El password no coincide",
                    required:"Campo Obligatorio"
                }
            }

        })
    });

</script>
</html>
