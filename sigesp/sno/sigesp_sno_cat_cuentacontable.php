<?php
	session_start();
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "close();";
		print "opener.document.form1.submit();";
		print "</script>";		
	}
	require_once("class_folder/class_funciones_nomina.php");
	$io_fun_nomina=new class_funciones_nomina();
	$ls_tipo=$io_fun_nomina->uf_obtenertipo();
	if (($ls_tipo=='PRESTACION')||($ls_tipo=='PRESTACIONINT'))
	{
		$ls_sc_cuenta=$_SESSION["la_empresa"]["pasivo"];
		$ls_readonly="readonly";
	}
	else
	{
		$ls_sc_cuenta="%%";
		$ls_readonly="";
	}

   //--------------------------------------------------------------
   function uf_print($as_sc_cuenta, $as_denominacion, $as_tipo)
   {
		//////////////////////////////////////////////////////////////////////////////
		//	     Function: uf_print
		//		   Access: public
		//	    Arguments: as_sc_cuenta  // C�digo de cuenta
		//				   as_denominacion  // Denominaci�n
		//				   as_tipo  // Tipo de Llamada del cat�logo
		//	  Description: Funci�n que obtiene e imprime los resultados de la busqueda
		//	   Creado Por: Ing. Yesenia Moreno
		// Fecha Creaci�n: 01/01/2006 								Fecha �ltima Modificaci�n : 
		//////////////////////////////////////////////////////////////////////////////
		require_once("../shared/class_folder/sigesp_include.php");
		$io_include=new sigesp_include();
		$io_conexion=$io_include->uf_conectar();
		require_once("../shared/class_folder/class_sql.php");
		$io_sql=new class_sql($io_conexion);	
		require_once("../shared/class_folder/class_mensajes.php");
		$io_mensajes=new class_mensajes();		
		require_once("../shared/class_folder/class_funciones.php");
		$io_funciones=new class_funciones();		
        $ls_codemp=$_SESSION["la_empresa"]["codemp"];
		
		if(array_key_exists("la_nomina",$_SESSION))
		{
			$ls_codnom=$_SESSION["la_nomina"]["codnom"];
		}
		else
		{
			$ls_codnom="0000";
		}
		print "<table width=500 border=0 cellpadding=1 cellspacing=1 class=fondo-tabla align=center>";
		print "<tr class=titulo-celda>";
		print "<td width=100>C�digo</td>";
		print "<td width=400>Denominaci�n</td>";
		print "</tr>";
		$ls_sql="SELECT sc_cuenta, denominacion ".
				"  FROM scg_cuentas ".
				" WHERE codemp='".$ls_codemp."'".
				"   AND status='C'".
				"   AND sc_cuenta like '".$as_sc_cuenta."' AND denominacion like '".$as_denominacion."'".
			    " ORDER BY sc_cuenta";
		$rs_data=$io_sql->select($ls_sql);
		if($rs_data===false)
		{
        	$io_mensajes->message("ERROR->".$io_funciones->uf_convertirmsg($io_sql->message)); 
		}
		else
		{
			while($row=$io_sql->fetch_row($rs_data))
			{
				$ls_sc_cuenta=$row["sc_cuenta"];
				$ls_denominacion=$row["denominacion"];
				switch ($as_tipo)
				{
					case "":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptar('$ls_sc_cuenta','$ls_denominacion');\">".$ls_sc_cuenta."</a></td>";
						print "<td>".$ls_denominacion."</td>";
						print "</tr>";			
						break;
	
					case "PATRONAL":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarpatronal('$ls_sc_cuenta','$ls_denominacion');\">".$ls_sc_cuenta."</a></td>";
						print "<td>".$ls_denominacion."</td>";
						print "</tr>";			
						break;

					case "CONFIGURACION":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarconfiguracion('$ls_sc_cuenta');\">".$ls_sc_cuenta."</a></td>";
						print "<td>".$ls_denominacion."</td>";
						print "</tr>";			
						break;

					case "CONFIGURACIONCAJA":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarconfiguracioncaja('$ls_sc_cuenta');\">".$ls_sc_cuenta."</a></td>";
						print "<td>".$ls_denominacion."</td>";
						print "</tr>";			
						break;

					case "NOMINA":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarnomina('$ls_sc_cuenta');\">".$ls_sc_cuenta."</a></td>";
						print "<td>".$ls_denominacion."</td>";
						print "</tr>";			
						break;

					case "FIDEICOMISO":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarfideicomiso('$ls_sc_cuenta');\">".$ls_sc_cuenta."</a></td>";
						print "<td>".$ls_denominacion."</td>";
						print "</tr>";			
						break;

					case "CONFIGURACIONPARAMETRO":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarconfiguracionparametro('$ls_sc_cuenta');\">".$ls_sc_cuenta."</a></td>";
						print "<td>".$ls_denominacion."</td>";
						print "</tr>";			
						break;

					case "PRESTACION":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarprestacion('$ls_sc_cuenta');\">".$ls_sc_cuenta."</a></td>";
						print "<td>".$ls_denominacion."</td>";
						print "</tr>";			
						break;

					case "PRESTACIONINT":
						print "<tr class=celdas-blancas>";
						print "<td><a href=\"javascript: aceptarprestacionint('$ls_sc_cuenta');\">".$ls_sc_cuenta."</a></td>";
						print "<td>".$ls_denominacion."</td>";
						print "</tr>";			
						break;
				}
			}
			$io_sql->free_result($rs_data);
		}
		print "</table>";
		unset($io_include);
		unset($io_conexion);
		unset($io_sql);
		unset($io_mensajes);
		unset($io_funciones);
		unset($ls_codemp);
		unset($ls_codnom);
   }
   //--------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Cat&aacute;logo de Cuentas Contables</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:active {
	color: #006699;
}
-->
</style>
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
</head>

<body>
<form name="form1" method="post" action="">
  <p align="center">
    <input name="operacion" type="hidden" id="operacion">
</p>
  <table width="500" border="0" align="center" cellpadding="1" cellspacing="1">
    <tr>
      <td width="496" height="20" colspan="2" class="titulo-ventana">Cat&aacute;logo de Cuentas Contables </td>
    </tr>
  </table>
<br>
    <table width="500" border="0" cellpadding="1" cellspacing="0" class="formato-blanco" align="center">
      <tr>
        <td width="67" height="22"><div align="right">N&uacute;mero</div></td>
        <td width="431"><div align="left">
          <input name="txtsc_cuenta" type="text" id="txtsc_cuenta" size="30" maxlength="25" onKeyPress="javascript: ue_mostrar(this,event);" value="<?php print $ls_sc_cuenta;?>" <?php print $ls_readonly;?>>        
        </div></td>
      </tr>
      <tr>
        <td height="22"><div align="right">Descripci&oacute;n</div></td>
        <td><div align="left">
          <input name="txtdenominacion" type="text" id="txtdenominacion" size="30" maxlength="100" onKeyPress="javascript: ue_mostrar(this,event);" <?php print $ls_readonly;?>>   
        </div></td>
      </tr>
      <tr>
        <td height="22">&nbsp;</td>
        <td><div align="right"><a href="javascript: ue_search();"><img src="../shared/imagebank/tools20/buscar.gif" title='Buscar' alt="Buscar" width="20" height="20" border="0"> Buscar</a></div></td>
      </tr>
  </table>
  <br>
<?php
	$ls_operacion =$io_fun_nomina->uf_obteneroperacion();
	if($ls_operacion=="BUSCAR")
	{
		$ls_sc_cuenta=$_POST["txtsc_cuenta"]."%";
		if (($ls_tipo!='PRESTACION')&&($ls_tipo!='PRESTACIONINT'))
		{
			$ls_sc_cuenta="%".$ls_sc_cuenta;
		}
		$ls_denominacion="%".$_POST["txtdenominacion"]."%";
		uf_print($ls_sc_cuenta, $ls_denominacion, $ls_tipo);
	}
	else
	{
		if (($ls_tipo=='PRESTACION')||($ls_tipo=='PRESTACIONINT'))
		{
			$ls_sc_cuenta=$ls_sc_cuenta."%";
		}
		$ls_denominacion="%%";
		uf_print($ls_sc_cuenta, $ls_denominacion, $ls_tipo);
	}
	unset($io_fun_nomina);
?>
</div>
</form>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
function aceptar(sc_cuenta,denominacion)
{
	opener.document.form1.txtcuecon.value=sc_cuenta;
    opener.document.form1.txtdencuecon.value=denominacion;
	close();
}

function aceptarpatronal(sc_cuenta,denominacion)
{
	opener.document.form1.txtcueconpat.value=sc_cuenta;
    opener.document.form1.txtdencueconpat.value=denominacion;
	close();
}

function aceptarconfiguracion(sc_cuenta)
{
	opener.document.form1.txtcuecon.value=sc_cuenta;
	opener.document.form1.txtcuecon.readOnly=true;
	close();
}

function aceptarconfiguracioncaja(sc_cuenta)
{
	opener.document.form1.txtcueconccaj.value=sc_cuenta;
	opener.document.form1.txtcueconccaj.readOnly=true;
	close();
}

function aceptarnomina(sc_cuenta)
{
	opener.document.form1.txtcueconnom.value=sc_cuenta;
	opener.document.form1.txtcueconnom.readOnly=true;
	close();
}

function aceptarfideicomiso(sc_cuenta)
{
	opener.document.form1.txtcueconfid.value=sc_cuenta;
	opener.document.form1.txtcueconfid.readOnly=true;
	close();
}

function aceptarconfiguracionparametro(sc_cuenta)
{
	opener.document.form1.txtcueconben.value=sc_cuenta;
	opener.document.form1.txtcueconben.readOnly=true;
	close();
}

function aceptarprestacion(sc_cuenta)
{
	opener.document.form1.txtscg_cuentafid.value=sc_cuenta;
	opener.document.form1.txtscg_cuentafid.readOnly=true;
	close();
}

function aceptarprestacionint(sc_cuenta)
{
	opener.document.form1.txtscg_cuentaintfid.value=sc_cuenta;
	opener.document.form1.txtscg_cuentaintfid.readOnly=true;
	close();
}

function ue_mostrar(myfield,e)
{
	var keycode;
	if (window.event) keycode = window.event.keyCode;
	else if (e) keycode = e.which;
	else return true;
	if (keycode == 13)
	{
		ue_search();
		return false;
	}
	else
		return true
}

function ue_search(existe)
{
	f=document.form1;
  	f.operacion.value="BUSCAR";
  	f.action="sigesp_sno_cat_cuentacontable.php?tipo=<?php print $ls_tipo;?>";
  	f.submit();
}
</script>
</html>
